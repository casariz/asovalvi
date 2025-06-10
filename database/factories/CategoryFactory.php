<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition(): array
    {
        $categories = [
            'Plantas de Interior',
            'Plantas de Exterior',
            'Árboles Frutales',
            'Plantas Aromáticas',
            'Flores de Temporada',
            'Cactus y Suculentas',
            'Herramientas de Jardín',
            'Fertilizantes',
            'Macetas y Contenedores',
            'Semillas'
        ];

        return [
            'name' => fake()->unique()->randomElement($categories),
            'description' => fake()->paragraph(),
            'image_url' => fake()->imageUrl(400, 300, 'plants'),
            'is_active' => true,
        ];
    }
}