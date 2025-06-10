<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Services\InventoryService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    protected $inventoryService;

    public function __construct(InventoryService $inventoryService)
    {
        $this->inventoryService = $inventoryService;
    }

    public function index(Request $request): JsonResponse
    {
        $query = Product::with('category');

        // Filtros
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->boolean('only_active')) {
            $query->active();
        }

        if ($request->boolean('in_stock')) {
            $query->inStock();
        }

        $products = $query->paginate($request->get('per_page', 15));

        return response()->json($products);
    }

    public function store(StoreProductRequest $request): JsonResponse
    {
        $product = Product::create($request->validated());
        $product->load('category');

        return response()->json([
            'message' => 'Producto creado exitosamente',
            'product' => $product
        ], 201);
    }

    public function show(Product $product): JsonResponse
    {
        $product->load(['category', 'inventoryMovements' => function ($query) {
            $query->latest()->take(10)->with('user:id,name');
        }]);

        return response()->json($product);
    }

    public function update(UpdateProductRequest $request, Product $product): JsonResponse
    {
        $product->update($request->validated());
        $product->load('category');

        return response()->json([
            'message' => 'Producto actualizado exitosamente',
            'product' => $product
        ]);
    }

    public function destroy(Product $product): JsonResponse
    {
        $product->delete();

        return response()->json([
            'message' => 'Producto eliminado exitosamente'
        ]);
    }

    public function updateStock(Request $request, Product $product): JsonResponse
    {
        $request->validate([
            'type' => 'required|in:add,remove,adjust',
            'quantity' => 'required|integer|min:1',
            'reason' => 'required|string|max:255',
        ]);

        $user = auth()->user();
        
        switch ($request->type) {
            case 'add':
                $movement = $this->inventoryService->addStock($product, $request->quantity, $request->reason, $user);
                break;
            case 'remove':
                $movement = $this->inventoryService->removeStock($product, $request->quantity, $request->reason, $user);
                break;
            case 'adjust':
                $movement = $this->inventoryService->adjustStock($product, $request->quantity, $request->reason, $user);
                break;
        }

        $product->refresh();        return response()->json([
            'message' => 'Stock actualizado exitosamente',
            'product' => $product,
            'movement' => $movement
        ]);
    }

    public function lowStock(Request $request): JsonResponse
    {
        $threshold = $request->get('threshold', 5);
        
        $products = Product::with('category')
            ->where('stock', '<=', $threshold)
            ->where('is_active', true)
            ->orderBy('stock', 'asc')
            ->get();

        return response()->json($products);
    }
}