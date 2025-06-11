<?php

namespace Database\Factories;

use App\Models\MeetingAssistant;
use App\Models\Meeting;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MeetingAssistant>
 */
class MeetingAssistantFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = MeetingAssistant::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'meeting_id' => Meeting::factory(),
            'assistant_name' => $this->faker->name(),
            'user_id' => $this->faker->boolean(70) ? User::factory() : null,
            'status' => $this->faker->randomElement([1, 2, 3])
        ];
    }

    /**
     * Indicate that the assistant is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 2
        ]);
    }

    /**
     * Indicate that the assistant is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 1
        ]);
    }

    /**
     * Indicate that the assistant has a user assigned.
     */
    public function withUser(): static
    {
        return $this->state(fn (array $attributes) => [
            'user_id' => User::factory()
        ]);
    }

    /**
     * Indicate that the assistant has no user assigned.
     */
    public function withoutUser(): static
    {
        return $this->state(fn (array $attributes) => [
            'user_id' => null
        ]);
    }
};