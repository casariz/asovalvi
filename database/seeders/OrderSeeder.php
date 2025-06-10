<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\Product;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $clients = User::where('role', 'client')->get();
        $products = Product::all();

        foreach ($clients->take(10) as $client) {
            // Create 1-3 orders per client
            $orderCount = rand(1, 3);
            
            for ($i = 0; $i < $orderCount; $i++) {
                $order = Order::create([
                    'order_number' => 'ORD-' . str_pad(Order::count() + 1, 6, '0', STR_PAD_LEFT),
                    'user_id' => $client->id,
                    'total_amount' => 0, // Will be calculated
                    'tax_amount' => 0,   // Will be calculated
                    'discount_amount' => rand(0, 20),
                    'status' => collect(['pending', 'confirmed', 'processing', 'shipped', 'delivered'])->random(),
                    'payment_status' => collect(['pending', 'paid'])->random(),
                    'payment_method' => collect(['cash', 'card', 'transfer'])->random(),
                    'shipping_address' => $client->address ?: 'Dirección de envío por defecto',
                    'notes' => rand(0, 1) ? 'Entregar en horario de mañana' : null,
                ]);

                // Add 1-5 items to each order
                $itemCount = rand(1, 5);
                $totalAmount = 0;

                for ($j = 0; $j < $itemCount; $j++) {
                    $product = $products->random();
                    $quantity = rand(1, 3);
                    $unitPrice = $product->price;
                    $totalPrice = $quantity * $unitPrice;

                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'quantity' => $quantity,
                        'unit_price' => $unitPrice,
                        'total_price' => $totalPrice,
                    ]);

                    $totalAmount += $totalPrice;
                }

                // Update order totals
                $taxAmount = $totalAmount * 0.12; // 12% IVA
                $finalAmount = $totalAmount + $taxAmount - $order->discount_amount;

                $order->update([
                    'total_amount' => $finalAmount,
                    'tax_amount' => $taxAmount,
                ]);
            }
        }
    }
}