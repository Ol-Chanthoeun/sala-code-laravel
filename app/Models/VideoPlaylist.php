<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VideoPlaylist extends Model
{
    protected $fillable = ['name', 'slug', 'description', 'thumbnail', 'status', 'order_number'];

    protected function casts(): array
    {
        return ['order_number' => 'integer'];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function videos(): HasMany
    {
        return $this->hasMany(Video::class, 'playlist_id')->orderBy('order_number')->orderBy('id');
    }

    public function publishedVideos(): HasMany
    {
        return $this->videos()->where('status', 'published');
    }

    public function getDisplayThumbnailAttribute(): string
    {
        if ($this->thumbnail) return asset('uploads/video-playlists/' . $this->thumbnail);

        $defaultLogo = match (strtolower($this->slug)) {
            'c', 'c-programming' => 'c.svg',
            'cpp', 'c-plus-plus', 'cplusplus' => 'cpp.svg',
            'python', 'python-programming' => 'python.svg',
            default => null,
        };

        return $defaultLogo
            ? asset('assets/images/video-playlists/' . $defaultLogo)
            : asset('assets/images/SalaCode-Logo.png');
    }
}
