<?php

namespace App\Http\Requests\Admin;

use App\Models\QuizCategory;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class QuizCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return in_array($this->user()?->role, [User::ROLE_ADMIN, User::ROLE_SUPER_ADMIN], true);
    }

    public function rules(): array
    {
        $category = $this->route('quiz_category');
        $languageId = $this->input('programming_language_id');

        return [
            'programming_language_id' => ['required', 'exists:programming_languages,id'],
            'title' => ['required', 'string', 'max:255'],
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('quiz_categories', 'slug')
                    ->where('programming_language_id', $languageId)
                    ->ignore($category instanceof QuizCategory ? $category->id : null),
            ],
            'description' => ['nullable', 'string'],
            'difficulty' => ['required', Rule::in(['Easy', 'Medium', 'Hard'])],
            'status' => ['required', Rule::in(['draft', 'published', 'archived'])],
            'order_number' => ['required', 'integer', 'min:1'],
        ];
    }
}
