<?php

namespace Database\Factories;

use App\Models\State;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\State>
 */
class StateFactory extends Factory
{
    protected $model = State::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'status_name' => $this->faker->randomElement([
                'Pendiente', 'Asignada', 'Completada', 'Rechazada',
                'Creado', 'Realizado',
                'Inactiva', 'Activa', 'Vencida'
            ]),
            'status_description' => $this->faker->sentence(),
            'context' => $this->faker->randomElement(['tasks', 'meetings', 'obligations']),
        ];
    }

    /**
     * State for tasks context
     */
    public function tasks()
    {
        return $this->state(function (array $attributes) {
            return [
                'context' => 'tasks',
                'status_name' => $this->faker->randomElement(['Pendiente', 'Asignada', 'Completada', 'Rechazada']),
            ];
        });
    }

    /**
     * State for meetings context
     */
    public function meetings()
    {
        return $this->state(function (array $attributes) {
            return [
                'context' => 'meetings',
                'status_name' => $this->faker->randomElement(['Creado', 'Realizado']),
            ];
        });
    }

    /**
     * State for obligations context
     */
    public function obligations()
    {
        return $this->state(function (array $attributes) {
            return [
                'context' => 'obligations',
                'status_name' => $this->faker->randomElement(['Inactiva', 'Activa', 'Pendiente', 'Vencida']),
            ];
        });
    }
}