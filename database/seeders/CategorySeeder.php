<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Plantas de Interior',
                'description' => 'Plantas perfectas para decorar y purificar el aire de tu hogar',
                'is_active' => true,
            ],
            [
                'name' => 'Plantas de Exterior',
                'description' => 'Plantas resistentes para jardines, patios y terrazas',
                'is_active' => true,
            ],
            [
                'name' => 'Árboles Frutales',
                'description' => 'Árboles frutales para crear tu propio huerto',
                'is_active' => true,
            ],
            [
                'name' => 'Plantas Aromáticas',
                'description' => 'Hierbas aromáticas para cocina y medicina natural',
                'is_active' => true,
            ],
            [
                'name' => 'Flores de Temporada',
                'description' => 'Flores coloridas para cada estación del año',
                'is_active' => true,
            ],
            [
                'name' => 'Cactus y Suculentas',
                'description' => 'Plantas de bajo mantenimiento, perfectas para principiantes',
                'is_active' => true,
            ],
            [
                'name' => 'Herramientas de Jardín',
                'description' => 'Todo lo necesario para el cuidado de tus plantas',
                'is_active' => true,
            ],
            [
                'name' => 'Fertilizantes y Sustratos',
                'description' => 'Nutrientes y tierras especializadas para el crecimiento óptimo',
                'is_active' => true,
            ],
            [
                'name' => 'Macetas y Contenedores',
                'description' => 'Macetas de diferentes tamaños y materiales',
                'is_active' => true,
            ],
            [
                'name' => 'Semillas',
                'description' => 'Semillas de alta calidad para cultivo propio',
                'is_active' => true,
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}