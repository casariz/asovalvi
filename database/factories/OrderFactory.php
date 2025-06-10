<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        $totalAmount = fake()->randomFloat(2, 20, 500);
        $taxAmount = $totalAmount * 0.12; // 12% IVA
        
        return [
            'order_number' => 'ORD-' . fake()->unique()->numerify('######'),
            'user_id' => User::factory(),
            'total_amount' => $totalAmount,
            'tax_amount' => $taxAmount,
            'discount_amount' => fake()->randomFloat(2, 0, 50),
            'status' => fake()->randomElement(['pending', 'confirmed', 'processing', 'shipped', 'delivered']),
            'payment_status' => fake()->randomElement(['pending', 'paid', 'failed']),
            'payment_method' => fake()->randomElement(['cash', 'card', 'transfer']),
            'shipping_address' => fake()->address(),
            'notes' => fake()->optional()->sentence(),
            'shipped_at' => fake()->optional()->dateTimeBetween('-1 month', 'now'),
            'delivered_at' => fake()->optional()->dateTimeBetween('-1 month', 'now'),
        ];
    }

    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
            'payment_status' => 'pending',
        ]);
    }

    public function delivered(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'delivered',
            'payment_status' => 'paid',
            'delivered_at' => fake()->dateTimeBetween('-1 month', 'now'),
        ]);
    }
}