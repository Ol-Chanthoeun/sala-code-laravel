<?php

namespace App\Http\Requests\Admin;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class LessonExampleRequest extends FormRequest
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
            'lesson_id' => ['required', 'exists:lessons,id'],
            'title' => ['required', 'string', 'max:255'],
            'source_code' => ['nullable', 'string'],
            'expected_output' => ['nullable', 'string'],
            'explanation' => ['nullable', 'string'],
        ];
    }
}
