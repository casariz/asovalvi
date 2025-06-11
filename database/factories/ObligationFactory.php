<?php

namespace Database\Factories;

use App\Models\Obligation;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Obligation>
 */
class ObligationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Obligation::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'obligation_description' => $this->faker->sentence(6),
            'category_id' => null,
            'server_name' => $this->faker->optional()->word(),
            'quantity' => $this->faker->numberBetween(100, 10000),
            'period' => $this->faker->randomElement(['MENSUAL', 'BIMESTRAL', 'TRIMESTRAL', 'SEMESTRAL', 'ANUAL', 'UNDEFINED']),
            'alert_time' => $this->faker->numberBetween(1, 30),
            'created_by' => User::factory(),
            'last_payment' => $this->faker->optional()->randomFloat(2, 0, 10000),
            'expiration_date' => $this->faker->optional()->dateTimeBetween('-1 month', '+3 months'),
            'observations' => $this->faker->paragraph(),
            'internal_reference' => $this->faker->optional()->regexify('[A-Z]{2}[0-9]{6}'),
            'reviewed_by' => $this->faker->optional()->randomElement([null, User::factory()]),
            'review_date' => $this->faker->optional()->dateTimeBetween('-1 month', 'now'),
            'status' => $this->faker->randomElement([10, 12, 13]),
        ];
    }

    /**
     * Indicate that the obligation is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 10,
        ]);
    }

    /**
     * Indicate that the obligation needs attention.
     */
    public function needsAttention(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 12,
        ]);
    }

    /**
     * Indicate that the obligation is expired.
     */
    public function expired(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 13,
            'expiration_date' => $this->faker->dateTimeBetween('-2 months', '-1 day'),
        ]);
    }
}