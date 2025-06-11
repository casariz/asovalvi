<?php

namespace Database\Factories;

use App\Models\Payment;
use App\Models\Obligation;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $dateIni = $this->faker->dateTimeBetween('-2 years', 'now');
        $dateEnd = $this->faker->optional(0.8)->dateTimeBetween($dateIni, '+1 month');

        return [
            'obligation_id' => Obligation::factory(),
            'date_ini' => $dateIni,
            'date_end' => $dateEnd,
            'paid' => $this->faker->randomFloat(2, 50, 5000),
            'observations' => $this->faker->optional(0.6)->sentence(),
            'created_by' => User::factory(),
            'creation_date' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'status' => $this->faker->randomElement([1, 2]),
        ];
    }

    /**
     * State for active payments
     */
    public function active()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 2,
            ];
        });
    }

    /**
     * State for deleted payments
     */
    public function deleted()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 1,
            ];
        });
    }

    /**
     * State for payments with end date
     */
    public function withEndDate()
    {
        return $this->state(function (array $attributes) {
            return [
                'date_end' => $this->faker->dateTimeBetween($attributes['date_ini'], '+3 months'),
            ];
        });
    }
}