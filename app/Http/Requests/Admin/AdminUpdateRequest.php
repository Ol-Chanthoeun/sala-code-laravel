<?php

namespace App\Http\Requests\Admin;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class AdminUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === User::ROLE_SUPER_ADMIN;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($this->route('admin'))],
            'password' => ['nullable', 'confirmed', Password::min(8)->mixedCase()->numbers()],
            'status' => ['required', Rule::in([User::STATUS_ACTIVE, User::STATUS_INACTIVE])],
        ];
    }
}
