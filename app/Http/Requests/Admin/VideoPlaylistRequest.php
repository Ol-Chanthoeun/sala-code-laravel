<?php

namespace App\Http\Requests\Admin;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;

class VideoPlaylistRequest extends FormRequest
{
    public function authorize(): bool
    {
        return in_array($this->user()?->role, [User::ROLE_ADMIN, User::ROLE_SUPER_ADMIN], true);
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('video_playlists', 'slug')->ignore($this->route('video_playlist'))],
            'description' => ['nullable', 'string'],
            'thumbnail' => ['nullable', File::image()->types(['jpg', 'jpeg', 'png', 'webp'])->max(2 * 1024)],
            'remove_thumbnail' => ['nullable', 'boolean'],
            'status' => ['required', Rule::in(['published', 'draft'])],
            'order_number' => ['required', 'integer', 'min:1'],
        ];
    }
}
