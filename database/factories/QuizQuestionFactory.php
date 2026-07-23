<?php

namespace Database\Factories;

use App\Models\Quiz;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuizQuestionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'quiz_id' => Quiz::factory(),
            'question' => fake()->sentence() . '?',
            'explanation' => fake()->sentence(),
            'difficulty' => fake()->randomElement(['Easy', 'Medium', 'Hard']),
            'points' => 1,
            'order_number' => fake()->numberBetween(1, 20),
            'correct_choice_id' => null,
        ];
    }
}
