<?php

namespace App\Http\Requests\Admin;

use App\Models\User;
use App\Models\Video;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;

class VideoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return in_array($this->user()?->role, [User::ROLE_ADMIN, User::ROLE_SUPER_ADMIN], true);
    }

    public function rules(): array
    {
        return [
            'playlist_id' => ['required', 'integer', 'exists:video_playlists,id'],
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'youtube_link' => ['required', 'url', 'max:2048', function (string $attribute, mixed $value, \Closure $fail): void {
                if (! Video::extractYoutubeId((string) $value)) {
                    $fail('The YouTube URL must be a valid youtube.com or youtu.be video URL.');
                }
            }],
            'thumbnail' => ['nullable', File::image()->types(['jpg', 'jpeg', 'png', 'webp'])->max(2 * 1024)],
            'order_number' => ['required', 'integer', 'min:1'],
            'duration' => ['nullable', 'string', 'max:30'],
            'status' => ['required', Rule::in(['published', 'draft'])],
        ];
    }
}
