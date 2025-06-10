<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TaskSeeder extends Seeder
{
    public function run(): void
    {        // Datos con estructura correcta de la tabla tasks
        $tasks = [
            [
                'meeting_id' => 1,
                'start_date' => now()->addDays(7)->toDateString(),
                'estimated_time' => 120, // minutos
                'units' => 'hours',
                'task_description' => 'Preparar agenda para reunión mensual - Recopilar temas a tratar',
                'assigned_to' => 3, // Secretario
                'observations' => 'Revisar temas pendientes del mes anterior',
                'created_by' => 1, // Admin
                'creation_date' => now(),
                'status' => 'PENDING',
            ],
            [
                'meeting_id' => 2,
                'start_date' => now()->addDays(15)->toDateString(),
                'estimated_time' => 240, // minutos
                'units' => 'hours',
                'task_description' => 'Revisar presupuesto anual - Analizar gastos e ingresos',
                'assigned_to' => 4, // Tesorero
                'observations' => 'Preparar informe detallado',
                'created_by' => 2, // Presidente
                'creation_date' => now(),
                'status' => 'PROGRESS',
            ],
            [
                'meeting_id' => 1,
                'start_date' => now()->addDays(30)->toDateString(),
                'estimated_time' => 180, // minutos
                'units' => 'hours',
                'task_description' => 'Organizar evento de intercambio de plantas',
                'assigned_to' => 5, // Miembro
                'observations' => 'Coordinar con viveros miembros',
                'created_by' => 2, // Presidente
                'creation_date' => now(),
                'status' => 'PENDING',
            ],
            [
                'meeting_id' => 1,
                'start_date' => now()->addDays(21)->toDateString(),
                'estimated_time' => 60, // minutos
                'units' => 'hours',
                'task_description' => 'Actualizar lista de contactos de miembros',
                'assigned_to' => 3, // Secretario
                'observations' => 'Verificar emails y teléfonos',
                'created_by' => 1, // Admin
                'creation_date' => now(),
                'status' => 'PENDING',
            ],            [
                'meeting_id' => 3,  // Cambiado de 3 a 3 (que sí existe)
                'start_date' => now()->subDays(5)->toDateString(),
                'estimated_time' => 90, // minutos
                'units' => 'hours',
                'task_description' => 'Redactar newsletter mensual',
                'assigned_to' => 6, // Miembro
                'observations' => 'Incluir noticias del sector',
                'created_by' => 3, // Secretario
                'creation_date' => now()->subDays(10),
                'reviewed_by' => 3,
                'review_date' => now()->subDays(2),
                'status' => 'COMPLETE',
            ],
        ];

        foreach ($tasks as $task) {
            DB::table('tasks')->insert($task);
        }
    }
}