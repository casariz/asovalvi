<?php

namespace Database\Factories;

use App\Models\MeetingTopic;
use App\Models\Meeting;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MeetingTopic>
 */
class MeetingTopicFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = MeetingTopic::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'meeting_id' => Meeting::factory(),
            'type' => $this->faker->randomElement(['General', 'Informativo', 'Decisión', 'Seguimiento']),
            'topic' => $this->faker->sentence(8),
            'created_by' => User::factory(),
            'creation_date' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'status' => $this->faker->randomElement([1, 2]),
        ];
    }

    /**
     * Indicate that the topic is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 2,
        ]);
    }

    /**
     * Indicate that the topic is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 1,
        ]);
    }
}