<?php

namespace App\Http\Requests\Admin;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class QuizQuestionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return in_array($this->user()?->role, [User::ROLE_ADMIN, User::ROLE_SUPER_ADMIN], true);
    }

    public function rules(): array
    {
        return [
            'quiz_id' => ['required', 'exists:quizzes,id'],
            'question' => ['required', 'string'],
            'explanation' => ['nullable', 'string'],
            'difficulty' => ['required', Rule::in(['Easy', 'Medium', 'Hard'])],
            'points' => ['required', 'integer', 'min:1', 'max:100'],
            'order_number' => ['required', 'integer', 'min:1'],
            'choices' => ['required', 'array', 'min:2'],
            'choices.*.choice_text' => ['required', 'string', 'max:1000'],
            'correct_choice' => ['required', 'integer', 'min:0'],
        ];
    }
}
