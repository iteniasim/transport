<?php

namespace Database\Factories;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Task::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->sentence(),
            'load_type' => $this->faker->word(),
            'from' => now()->subMonth(),
            'to' => now(),
            'weight' => $this->faker->numberBetween(1, 500),
            'cost' => $this->faker->numberBetween(500, 1000),
            'status' => $this->faker->numberBetween(0, 3), // Adjust based on the range of statuses
            'user_id' => User::query()->inRandomOrder()->value('id'), // Assign to a random user or null
            'created_by' => User::query()->inRandomOrder()->value('id'), // Ensure at least one user exists
            'updated_by' => $this->faker->boolean() ? User::query()->inRandomOrder()->value('id') : null,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
