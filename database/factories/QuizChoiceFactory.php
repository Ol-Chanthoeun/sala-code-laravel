<?php

namespace Database\Factories;

use App\Models\QuizQuestion;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuizChoiceFactory extends Factory
{
    public function definition(): array
    {
        return [
            'quiz_question_id' => QuizQuestion::factory(),
            'choice_text' => fake()->sentence(),
            'is_correct' => false,
            'order_number' => fake()->numberBetween(1, 4),
        ];
    }
}
