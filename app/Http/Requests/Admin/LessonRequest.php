<?php

namespace App\Http\Requests\Admin;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LessonRequest extends FormRequest
{
    public function authorize(): bool
    {
        return in_array($this->user()?->role, [User::ROLE_ADMIN, User::ROLE_SUPER_ADMIN], true);
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'course_id' => ['required', 'exists:courses,id'],
            'section_id' => ['nullable', 'exists:course_sections,id'],
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('lessons', 'slug')->ignore($this->route('lesson'))],
            'short_description' => ['nullable', 'string'],
            'lesson_content' => ['nullable', 'string'],
            'source_code' => ['nullable', 'string'],
            'expected_output' => ['nullable', 'string'],
            'explanation' => ['nullable', 'string'],
            'code_explanation' => ['nullable', 'string'],
            'common_mistakes' => ['nullable', 'string'],
            'tips' => ['nullable', 'string'],
            'summary' => ['nullable', 'string'],
            'exercise' => ['nullable', 'string'],
            'quiz' => ['nullable', 'array'],
            'quiz.*.question' => ['nullable', 'string'],
            'quiz.*.options' => ['nullable', 'array', 'size:4'],
            'quiz.*.options.*' => ['nullable', 'string'],
            'quiz.*.answer' => ['nullable', 'string'],
            'difficulty_level' => ['nullable', Rule::in(['Beginner', 'Intermediate', 'Advanced'])],
            'estimated_learning_time' => ['nullable', 'integer', 'min:1', 'max:600'],
            'video_url' => ['nullable', 'url', 'max:255'],
            'order_number' => ['required', 'integer', 'min:1'],
            'status' => ['required', Rule::in(['draft', 'published', 'unpublished'])],
        ];
    }
}
