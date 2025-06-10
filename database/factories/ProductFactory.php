<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $plants = [
            'Rosa Roja', 'Lavanda', 'Romero', 'Albahaca', 'Geranio',
            'Petunias', 'Begonias', 'Ficus', 'Monstera', 'Pothos',
            'Suculenta Echeveria', 'Cactus Barrel', 'Limonero', 'Naranjo',
            'Manzano', 'Tomillo', 'Orégano', 'Menta', 'Perejil', 'Cilantro'
        ];

        return [
            'name' => fake()->randomElement($plants),
            'description' => fake()->paragraph(3),
            'price' => fake()->randomFloat(2, 5, 150),
            'stock' => fake()->numberBetween(0, 100),
            'sku' => fake()->unique()->regexify('[A-Z]{3}[0-9]{6}'),
            'images' => json_encode([
                fake()->imageUrl(400, 400, 'plants'),
                fake()->imageUrl(400, 400, 'plants'),
            ]),
            'category_id' => Category::factory(),
            'is_active' => true,
            'weight' => fake()->randomFloat(2, 0.1, 10),
            'size' => fake()->randomElement(['Pequeña', 'Mediana', 'Grande']),
            'care_instructions' => fake()->paragraph(2),
            'status' => fake()->randomElement(['available', 'out_of_stock']),
        ];
    }

    public function outOfStock(): static
    {
        return $this->state(fn (array $attributes) => [
            'stock' => 0,
            'status' => 'out_of_stock',
        ]);
    }
}