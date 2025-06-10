<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::all();

        foreach ($categories as $category) {
            // Create 5-10 products per category
            Product::factory()
                ->count(rand(5, 10))
                ->create(['category_id' => $category->id]);
        }

        // Create some specific featured products
        $interiorCategory = Category::where('name', 'Plantas de Interior')->first();
        if ($interiorCategory) {
            Product::create([
                'name' => 'Monstera Deliciosa',
                'description' => 'Planta tropical de hojas grandes y perforadas, perfecta para interiores luminosos.',
                'price' => 35.99,
                'stock' => 15,
                'sku' => 'MON001234',
                'images' => json_encode(['monstera1.jpg', 'monstera2.jpg']),
                'category_id' => $interiorCategory->id,
                'is_active' => true,
                'weight' => 2.5,
                'size' => 'Mediana',
                'care_instructions' => 'Regar cuando la tierra esté seca. Luz indirecta brillante.',
                'status' => 'available',
            ]);
        }

        $fruitCategory = Category::where('name', 'Árboles Frutales')->first();
        if ($fruitCategory) {
            Product::create([
                'name' => 'Limonero Meyer',
                'description' => 'Árbol frutal compacto, ideal para macetas. Produce limones dulces.',
                'price' => 45.00,
                'stock' => 8,
                'sku' => 'LIM001234',
                'images' => json_encode(['limonero1.jpg', 'limonero2.jpg']),
                'category_id' => $fruitCategory->id,
                'is_active' => true,
                'weight' => 5.0,
                'size' => 'Grande',
                'care_instructions' => 'Pleno sol, riego regular pero sin encharcamiento.',
                'status' => 'available',
            ]);
        }
    }
}