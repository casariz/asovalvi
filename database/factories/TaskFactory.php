<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Meeting;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    public function definition(): array
    {
        $users = User::pluck('id')->toArray();
        $meetings = Meeting::pluck('meeting_id')->toArray();

        return [
            'meeting_id' => fake()->randomElement([null, ...$meetings]),
            'start_date' => fake()->dateTimeBetween('now', '+1 month'),
            'estimated_time' => fake()->numberBetween(1, 8),
            'units' => fake()->randomElement(['horas', 'días', 'semanas']),
            'task_description' => fake()->randomElement([
                'Revisar inventario de plantas',
                'Actualizar sistema de riego',
                'Preparar reporte mensual',
                'Capacitación del personal',
                'Mantenimiento de invernaderos',
                'Control de plagas y enfermedades',
                'Coordinación con proveedores',
                'Evaluación de calidad de plantas'
            ]),
            'assigned_to' => fake()->randomElement([null, ...$users]),
            'observations' => fake()->optional()->sentence(),
            'created_by' => fake()->randomElement($users),
            'creation_date' => now(),
            'reviewed_by' => fake()->optional()->randomElement($users),
            'review_date' => fake()->optional()->dateTimeBetween('now', '+2 weeks'),
            'state_id' => fake()->randomElement([1, 2, 3, 4]) // Pendiente, Asignada, Completada, Rechazada
        ];
    }

    /**
     * Estado: Tareas sin asignar
     */
    public function unassigned()
    {
        return $this->state(fn (array $attributes) => [
            'assigned_to' => null,
            'state_id' => 1 // Pendiente
        ]);
    }

    /**
     * Estado: Tareas asignadas
     */
    public function assigned()
    {
        $users = User::pluck('id')->toArray();

        return $this->state(fn (array $attributes) => [
            'assigned_to' => fake()->randomElement($users),
            'state_id' => 2 // Asignada
        ]);
    }

    /**
     * Estado: Tareas completadas
     */
    public function completed()
    {
        $users = User::pluck('id')->toArray();

        return $this->state(fn (array $attributes) => [
            'assigned_to' => fake()->randomElement($users),
            'reviewed_by' => fake()->randomElement($users),
            'review_date' => now()->addDays(fake()->numberBetween(1, 7)),
            'state_id' => 3 // Completada
        ]);
    }

    /**
     * Estado: Tareas rechazadas
     */
    public function rejected()
    {
        $users = User::pluck('id')->toArray();

        return $this->state(fn (array $attributes) => [
            'assigned_to' => fake()->randomElement($users),
            'reviewed_by' => fake()->randomElement($users),
            'review_date' => now()->addDays(fake()->numberBetween(1, 7)),
            'state_id' => 4 // Rechazada
        ]);
    }
}
