<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Course>
 */
class CourseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "course_name" => fake()->name,
            "course_duration" => fake()->numberBetween(1, 6),
            "course_fee" => fake()->numberBetween(25000, 50000),
        ];
    }
}
