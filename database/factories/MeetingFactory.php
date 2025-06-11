<?php

namespace Database\Factories;

use App\Models\Meeting;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Meeting>
 */
class MeetingFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Meeting::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'meeting_date' => $this->faker->date(),
            'start_hour' => $this->faker->time(),
            'called_by' => $this->faker->name(),
            'director' => $this->faker->name(),
            'secretary' => $this->faker->name(),
            'placement' => $this->faker->address(),
            'meeting_description' => $this->faker->paragraph(),
            'empty_field' => null,
            'topics' => $this->faker->text(),
            'created_by' => $this->faker->name(),
            'creation_date' => $this->faker->dateTime(),
            'status' => $this->faker->numberBetween(1, 3),
        ];
    }
}