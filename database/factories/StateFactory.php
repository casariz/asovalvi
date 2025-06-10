<?php

namespace Database\Factories;

use App\Models\State;
use Illuminate\Database\Eloquent\Factories\Factory;

class StateFactory extends Factory
{
    protected $model = State::class;

    public function definition(): array
    {
        return [
            'status' => $this->faker->unique()->randomElement(['ACTIVE', 'INACTIVE', 'PENDING', 'COMPLETE', 'CANCELLED']),
            'description' => $this->faker->words(3, true),
        ];
    }
}