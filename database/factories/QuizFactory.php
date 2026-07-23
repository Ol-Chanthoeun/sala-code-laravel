<?php

namespace Database\Factories;

use App\Models\ProgrammingLanguage;
use App\Models\QuizCategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class QuizFactory extends Factory
{
    public function definition(): array
    {
        $title = fake()->unique()->words(3, true);

        return [
            'programming_language_id' => ProgrammingLanguage::factory(),
            'quiz_category_id' => QuizCategory::factory(),
            'title' => ucfirst($title),
            'slug' => Str::slug($title),
            'description' => fake()->sentence(),
            'difficulty' => fake()->randomElement(['Easy', 'Medium', 'Hard']),
            'estimated_time' => fake()->numberBetween(5, 60),
            'passing_score' => 60,
            'status' => 'published',
            'order_number' => fake()->numberBetween(1, 20),
        ];
    }
}
