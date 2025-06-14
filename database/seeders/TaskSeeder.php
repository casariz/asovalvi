<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\User;
use App\Models\Meeting;
use App\Models\State;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear usuarios y reuniones si no existen
        if (User::count() === 0) {
            User::factory(10)->create();
        }

        if (Meeting::count() === 0) {
            Meeting::factory(5)->create();
        }

        // Crear tareas sin asignar
        Task::factory()->unassigned()->count(10)->create();

        // Crear diferentes tipos de tareas
        Task::factory(15)->assigned()->create();
        Task::factory(8)->completed()->create();
        Task::factory(3)->rejected()->create();

        // Crear tareas sin reuniones (independientes)
        Task::factory(5)->create([
            'meeting_id' => null,
        ]);

        $users = User::all();
        $meetings = Meeting::all();

        if ($users->count() > 0 && $meetings->count() > 0) {
            // Crear tareas manuales

            Task::create([
                'meeting_id' => $meetings->first()->meeting_id,
                'start_date' => now()->addDays(1),
                'estimated_time' => 2,
                'units' => 'horas',
                'task_description' => 'Revisar inventario de plantas ornamentales',
                'assigned_to' => $users->random()->id,
                'observations' => 'Prioridad alta - inventario mensual',
                'created_by' => $users->first()->id,
                'creation_date' => now(),
                'status' => 2 // Asignada
            ]);

            Task::create([
                'meeting_id' => null,
                'start_date' => now()->addDays(3),
                'estimated_time' => 4,
                'units' => 'horas',
                'task_description' => 'Preparar reporte de ventas del trimestre',
                'assigned_to' => null,
                'observations' => 'Incluir comparativo con trimestre anterior',
                'created_by' => $users->first()->id,
                'creation_date' => now(),
                'status' => 1 // Pendiente
            ]);

            Task::create([
                'meeting_id' => $meetings->count() > 1 ? $meetings->skip(1)->first()->meeting_id : $meetings->first()->meeting_id,
                'start_date' => now()->addDays(7),
                'estimated_time' => 1,
                'units' => 'días',
                'task_description' => 'Capacitación en nuevas técnicas de cultivo',
                'assigned_to' => $users->random()->id,
                'observations' => 'Coordinar con especialista externo',
                'created_by' => $users->first()->id,
                'creation_date' => now(),
                'reviewed_by' => $users->random()->id,
                'review_date' => now()->addDays(5),
                'status' => 3 // Completada
            ]);
        }
    }
}
