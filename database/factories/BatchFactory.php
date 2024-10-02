<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Batch>
 */
class BatchFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "batch_no" => fake()->numberBetween(0, 9999),
            "teacher" => User::where('role', 'teacher')->value('id'),
            "course_id" => Course::inRandomOrder()->first()->id
        ];
    }
}
