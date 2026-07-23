<?php

namespace Database\Factories;

use App\Models\ProgrammingLanguage;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class QuizCategoryFactory extends Factory
{
    public function definition(): array
    {
        $title = fake()->unique()->words(2, true);

        return [
            'programming_language_id' => ProgrammingLanguage::factory(),
            'title' => ucfirst($title),
            'slug' => Str::slug($title),
            'description' => fake()->sentence(),
            'difficulty' => fake()->randomElement(['Easy', 'Medium', 'Hard']),
            'status' => 'published',
            'order_number' => fake()->numberBetween(1, 20),
        ];
    }
}
