<?php

namespace App\Services;

use App\Models\Product;
use App\Models\InventoryMovement;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class InventoryService
{
    public function addStock(Product $product, int $quantity, string $reason, User $user): InventoryMovement
    {
        return DB::transaction(function () use ($product, $quantity, $reason, $user) {
            $previousStock = $product->stock;
            $newStock = $previousStock + $quantity;

            $product->update(['stock' => $newStock]);

            if ($newStock > 0 && $product->status === 'out_of_stock') {
                $product->update(['status' => 'available']);
            }

            return InventoryMovement::create([
                'product_id' => $product->id,
                'type' => 'in',
                'quantity' => $quantity,
                'previous_stock' => $previousStock,
                'new_stock' => $newStock,
                'reason' => $reason,
                'user_id' => $user->id,
            ]);
        });
    }

    public function removeStock(Product $product, int $quantity, string $reason, User $user): InventoryMovement
    {
        return DB::transaction(function () use ($product, $quantity, $reason, $user) {
            $previousStock = $product->stock;
            $newStock = max(0, $previousStock - $quantity);

            $product->update(['stock' => $newStock]);

            if ($newStock === 0) {
                $product->update(['status' => 'out_of_stock']);
            }

            return InventoryMovement::create([
                'product_id' => $product->id,
                'type' => 'out',
                'quantity' => $quantity,
                'previous_stock' => $previousStock,
                'new_stock' => $newStock,
                'reason' => $reason,
                'user_id' => $user->id,
            ]);
        });
    }

    public function adjustStock(Product $product, int $newStock, string $reason, User $user): InventoryMovement
    {
        return DB::transaction(function () use ($product, $newStock, $reason, $user) {
            $previousStock = $product->stock;
            $quantity = $newStock - $previousStock;

            $product->update(['stock' => $newStock]);

            if ($newStock > 0 && $product->status === 'out_of_stock') {
                $product->update(['status' => 'available']);
            } elseif ($newStock === 0) {
                $product->update(['status' => 'out_of_stock']);
            }

            return InventoryMovement::create([
                'product_id' => $product->id,
                'type' => 'adjustment',
                'quantity' => abs($quantity),
                'previous_stock' => $previousStock,
                'new_stock' => $newStock,
                'reason' => $reason,
                'user_id' => $user->id,
            ]);
        });
    }

    public function processOrderStock(array $orderItems, User $user): array
    {
        $movements = [];

        DB::transaction(function () use ($orderItems, $user, &$movements) {
            foreach ($orderItems as $item) {
                $product = Product::find($item['product_id']);
                if ($product && $product->stock >= $item['quantity']) {
                    $movements[] = $this->removeStock(
                        $product,
                        $item['quantity'],
                        "Venta - Orden #{$item['order_number']}",
                        $user
                    );
                }
            }
        });

        return $movements;
    }
}