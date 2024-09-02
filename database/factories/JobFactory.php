<?php

namespace Database\Factories;

use App\Models\Job;
use Illuminate\Database\Eloquent\Factories\Factory;

class JobFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Job::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => \App\Models\User::factory(), // Assumes the User factory exists
            'title' => $this->faker->jobTitle,
            'description' => $this->faker->paragraph,
            'location' => $this->faker->city,
            'work_type' => $this->faker->randomElement(['remote', 'on-site']),
            'category' => $this->faker->word,
            'salary' => $this->faker->randomFloat(2, 30000, 100000),
            'deadline' => $this->faker->dateTimeBetween('now', '+1 year'),
        ];
    }
}
