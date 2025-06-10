<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusDescriptionSeeder extends Seeder
{
    public function run(): void
    {        $statuses = [
            ['status' => 'ACTIVE', 'description' => 'Activo'],
            ['status' => 'INACTIVE', 'description' => 'Inactivo'],
            ['status' => 'PENDING', 'description' => 'Pendiente'],
            ['status' => 'COMPLETE', 'description' => 'Completado'],
            ['status' => 'CANCELLED', 'description' => 'Cancelado'],
            ['status' => 'SCHEDULED', 'description' => 'Programado'],
            ['status' => 'PROGRESS', 'description' => 'En Progreso'],
            ['status' => 'REVIEW', 'description' => 'En Revisión'],
        ];

        foreach ($statuses as $status) {
            DB::table('status_descriptions')->insert($status);
        }
    }
}