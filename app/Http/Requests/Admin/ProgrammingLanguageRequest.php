<?php

namespace App\Http\Requests\Admin;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;

class ProgrammingLanguageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return in_array($this->user()?->role, [User::ROLE_ADMIN, User::ROLE_SUPER_ADMIN], true);
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('programming_languages', 'slug')->ignore($this->route('programming_language'))],
            'logo' => ['nullable', File::types(['png', 'jpg', 'jpeg', 'svg', 'webp'])->max(2 * 1024)],
            'remove_logo' => ['nullable', 'boolean'],
            'description' => ['nullable', 'string'],
            'difficulty' => ['required', Rule::in(['Easy', 'Medium', 'Hard', 'Beginner', 'Intermediate', 'Advanced'])],
            'estimated_time' => ['required', 'integer', 'min:1', 'max:10000'],
            'status' => ['required', Rule::in(['draft', 'published', 'archived'])],
            'order_number' => ['required', 'integer', 'min:1'],
        ];
    }
}
