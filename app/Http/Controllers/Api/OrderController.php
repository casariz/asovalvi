<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Services\InventoryService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    protected $inventoryService;

    public function __construct(InventoryService $inventoryService)
    {
        $this->inventoryService = $inventoryService;
    }

    public function index(Request $request): JsonResponse
    {
        $query = Order::with(['user:id,name,email', 'orderItems.product:id,name,price']);

        // Solo admins y managers ven todas las órdenes, clients solo las suyas
        if (auth()->user()->role === 'client') {
            $query->where('user_id', auth()->id());
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->has('user_id') && auth()->user()->role !== 'client') {
            $query->where('user_id', $request->user_id);
        }

        $orders = $query->latest()->paginate($request->get('per_page', 15));

        return response()->json($orders);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'shipping_address' => 'required|string',
            'payment_method' => 'nullable|in:cash,card,transfer,other',
            'notes' => 'nullable|string',
        ]);

        return DB::transaction(function () use ($request) {
            // Verificar stock disponible
            foreach ($request->items as $item) {
                $product = Product::find($item['product_id']);
                if (!$product || $product->stock < $item['quantity']) {
                    return response()->json([
                        'message' => "Stock insuficiente para el producto: {$product->name}"
                    ], 422);
                }
            }

            // Calcular totales
            $subtotal = 0;
            foreach ($request->items as $item) {
                $product = Product::find($item['product_id']);
                $subtotal += $product->price * $item['quantity'];
            }

            $taxAmount = $subtotal * 0.12; // 12% IVA
            $totalAmount = $subtotal + $taxAmount;

            // Crear orden
            $order = Order::create([
                'order_number' => 'ORD-' . str_pad(Order::count() + 1, 6, '0', STR_PAD_LEFT),
                'user_id' => auth()->id(),
                'total_amount' => $totalAmount,
                'tax_amount' => $taxAmount,
                'discount_amount' => 0,
                'status' => 'pending',
                'payment_status' => 'pending',
                'payment_method' => $request->payment_method,
                'shipping_address' => $request->shipping_address,
                'notes' => $request->notes,
            ]);

            // Crear items de la orden
            foreach ($request->items as $item) {
                $product = Product::find($item['product_id']);
                $totalPrice = $product->price * $item['quantity'];

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'unit_price' => $product->price,
                    'total_price' => $totalPrice,
                ]);
            }

            $order->load(['orderItems.product', 'user']);

            return response()->json([
                'message' => 'Orden creada exitosamente',
                'order' => $order
            ], 201);
        });
    }

    public function show(Order $order): JsonResponse
    {
        // Los clients solo pueden ver sus propias órdenes
        if (auth()->user()->role === 'client' && $order->user_id !== auth()->id()) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $order->load(['user:id,name,email,phone', 'orderItems.product']);

        return response()->json($order);
    }

    public function update(Request $request, Order $order): JsonResponse
    {
        // Solo admins y managers pueden actualizar órdenes
        if (!in_array(auth()->user()->role, ['admin', 'manager'])) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $request->validate([
            'status' => 'nullable|in:pending,confirmed,processing,shipped,delivered,cancelled',
            'payment_status' => 'nullable|in:pending,paid,failed,refunded',
            'payment_method' => 'nullable|in:cash,card,transfer,other',
            'shipping_address' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $order->update($request->validated());

        return response()->json([
            'message' => 'Orden actualizada exitosamente',
            'order' => $order
        ]);
    }

    public function confirm(Order $order): JsonResponse
    {
        if ($order->status !== 'pending') {
            return response()->json(['message' => 'La orden no puede ser confirmada'], 422);
        }

        // Reducir stock al confirmar
        $orderItems = $order->orderItems()->with('product')->get();
        foreach ($orderItems as $item) {
            $this->inventoryService->removeStock(
                $item->product,
                $item->quantity,
                "Venta - Orden #{$order->order_number}",
                auth()->user()
            );
        }

        $order->update([
            'status' => 'confirmed',
            'payment_status' => 'paid'
        ]);

        return response()->json([
            'message' => 'Orden confirmada exitosamente',
            'order' => $order
        ]);
    }

    public function ship(Order $order): JsonResponse
    {
        if (!in_array($order->status, ['confirmed', 'processing'])) {
            return response()->json(['message' => 'La orden no puede ser enviada'], 422);
        }

        $order->update([
            'status' => 'shipped',
            'shipped_at' => now()
        ]);

        return response()->json([
            'message' => 'Orden marcada como enviada',
            'order' => $order
        ]);
    }

    public function deliver(Order $order): JsonResponse
    {
        if ($order->status !== 'shipped') {
            return response()->json(['message' => 'La orden no puede ser entregada'], 422);
        }

        $order->update([
            'status' => 'delivered',
            'delivered_at' => now()
        ]);

        return response()->json([
            'message' => 'Orden marcada como entregada',
            'order' => $order
        ]);
    }

    public function cancel(Order $order): JsonResponse
    {
        if (!in_array($order->status, ['pending', 'confirmed'])) {
            return response()->json(['message' => 'La orden no puede ser cancelada'], 422);
        }

        // Si ya se redujo stock, devolverlo
        if ($order->status === 'confirmed') {
            $orderItems = $order->orderItems()->with('product')->get();
            foreach ($orderItems as $item) {
                $this->inventoryService->addStock(
                    $item->product,
                    $item->quantity,
                    "Devolución - Cancelación orden #{$order->order_number}",
                    auth()->user()
                );
            }
        }

        $order->update([
            'status' => 'cancelled',
            'payment_status' => 'refunded'
        ]);

        return response()->json([
            'message' => 'Orden cancelada exitosamente',
            'order' => $order
        ]);
    }

    public function stats(): JsonResponse
    {
        $stats = [
            'total_orders' => Order::count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'confirmed_orders' => Order::where('status', 'confirmed')->count(),
            'delivered_orders' => Order::where('status', 'delivered')->count(),
            'monthly_revenue' => Order::where('status', 'delivered')
                ->whereMonth('created_at', now()->month)
                ->sum('total_amount'),
            'low_stock_products' => Product::where('stock', '<=', 5)->count(),
        ];

        return response()->json($stats);
    }
}