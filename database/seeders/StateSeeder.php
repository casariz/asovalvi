<?php

namespace Database\Seeders;

use App\Models\State;
use Illuminate\Database\Seeder;

class StateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Task statuses
        $taskStatuses = [
            ['status' => 1, 'status_name' => 'Pendiente', 'status_description' => 'Tarea pendiente de asignación', 'context' => 'tasks'],
            ['status' => 2, 'status_name' => 'Asignada', 'status_description' => 'Tarea asignada a un usuario', 'context' => 'tasks'],
            ['status' => 3, 'status_name' => 'Completada', 'status_description' => 'Tarea completada exitosamente', 'context' => 'tasks'],
            ['status' => 4, 'status_name' => 'Rechazada', 'status_description' => 'Tarea rechazada', 'context' => 'tasks'],
        ];

        // Meeting statuses
        $meetingStatuses = [
            ['status' => 5, 'status_name' => 'Creado', 'status_description' => 'Reunión creada', 'context' => 'meetings'],
            ['status' => 6, 'status_name' => 'Realizado', 'status_description' => 'Reunión realizada', 'context' => 'meetings'],
        ];

        // Obligation statuses
        $obligationStatuses = [
            ['status' => 7, 'status_name' => 'Inactiva', 'status_description' => 'Obligación inactiva', 'context' => 'obligations'],
            ['status' => 8, 'status_name' => 'Activa', 'status_description' => 'Obligación activa', 'context' => 'obligations'],
            ['status' => 9, 'status_name' => 'Pendiente', 'status_description' => 'Obligación pendiente de pago', 'context' => 'obligations'],
            ['status' => 10, 'status_name' => 'Vencida', 'status_description' => 'Obligación vencida', 'context' => 'obligations'],
        ];

        // General statuses
        $generalStatuses = [
            ['status' => 11, 'status_name' => 'Eliminado', 'status_description' => 'Registro eliminado', 'context' => 'general'],
            ['status' => 12, 'status_name' => 'Activo', 'status_description' => 'Registro activo', 'context' => 'general'],
        ];

        $allStatuses = array_merge($taskStatuses, $meetingStatuses, $obligationStatuses, $generalStatuses);

        foreach ($allStatuses as $status) {
            State::updateOrCreate(
                ['status' => $status['status']], // Aquí buscamos por el ID único
                $status
            );
        }
    }
}
