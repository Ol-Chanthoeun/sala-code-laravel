<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProgrammingLanguageFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->unique()->word();

        return [
            'name' => ucfirst($name),
            'slug' => Str::slug($name),
            'logo' => null,
            'description' => fake()->sentence(),
            'difficulty' => fake()->randomElement(['Beginner', 'Intermediate', 'Advanced']),
            'estimated_time' => fake()->numberBetween(30, 300),
            'status' => 'published',
            'order_number' => fake()->numberBetween(1, 20),
        ];
    }
}
