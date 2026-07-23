<?php

namespace App\Http\Requests\Admin;

use App\Models\Quiz;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LmsQuizRequest extends FormRequest
{
    public function authorize(): bool
    {
        return in_array($this->user()?->role, [User::ROLE_ADMIN, User::ROLE_SUPER_ADMIN], true);
    }

    public function rules(): array
    {
        $quiz = $this->route('quiz');
        $categoryId = $this->input('quiz_category_id');

        return [
            'programming_language_id' => ['required', 'exists:programming_languages,id'],
            'quiz_category_id' => ['required', 'exists:quiz_categories,id'],
            'title' => ['required', 'string', 'max:255'],
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('quizzes', 'slug')
                    ->where('quiz_category_id', $categoryId)
                    ->ignore($quiz instanceof Quiz ? $quiz->id : null),
            ],
            'description' => ['nullable', 'string'],
            'difficulty' => ['required', Rule::in(['Easy', 'Medium', 'Hard'])],
            'estimated_time' => ['required', 'integer', 'min:1', 'max:600'],
            'passing_score' => ['required', 'integer', 'min:1', 'max:100'],
            'status' => ['required', Rule::in(['draft', 'published', 'archived'])],
            'order_number' => ['required', 'integer', 'min:1'],
        ];
    }
}
