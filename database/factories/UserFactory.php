<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    protected static ?string $password;

    public function definition(): array
    {
        return [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->unique()->safeEmail(),
            'password' => static::$password ??= Hash::make('password'),
            'position' => fake()->randomElement(['Gerente', 'Coordinador', 'Asistente', 'Técnico', 'Supervisor']),
            'phone' => fake()->phoneNumber(),
            'status' => fake()->randomElement([1, 2]) // 1: Activo, 2: Inactivo
        ];
    }

    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 2,
        ]);
    }
}