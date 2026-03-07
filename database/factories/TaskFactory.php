<?php

namespace Database\Factories;

use App\Models\Status;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'status_id' => Status::inRandomOrder()->first()->id,
            'created_by' => 1,
            'sprint_id' => $this->faker->randomElement([1, 2, 3, 4, 5]),
            'assigned_to' => $this->faker->randomElement([1, 2, 3, 4, 5]),
        ];
    }
}
