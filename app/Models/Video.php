<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Video extends Model
{
    protected $fillable = [
        'programming_language_id',
        'playlist_id',
        'title',
        'playlist_title',
        'playlist_slug',
        'slug',
        'description',
        'order_number',
        'duration',
        'status',
        'youtube_link',
        'youtube_video_id',
        'thumbnail',
    ];

    protected function casts(): array
    {
        return ['order_number' => 'integer'];
    }

    public function programmingLanguage(): BelongsTo
    {
        return $this->belongsTo(ProgrammingLanguage::class);
    }

    public function playlist(): BelongsTo
    {
        return $this->belongsTo(VideoPlaylist::class, 'playlist_id');
    }

    public static function extractYoutubeId(?string $url): ?string
    {
        if (! $url) {
            return null;
        }

        $parts = parse_url(trim($url));
        $host = strtolower($parts['host'] ?? '');
        $path = trim($parts['path'] ?? '', '/');
        $videoId = null;

        if (in_array($host, ['youtu.be', 'www.youtu.be'], true)) {
            $videoId = explode('/', $path)[0] ?? null;
        } elseif (in_array($host, ['youtube.com', 'www.youtube.com', 'm.youtube.com'], true)) {
            parse_str($parts['query'] ?? '', $query);
            $videoId = $query['v'] ?? null;
            if (! $videoId && preg_match('~^(?:embed|shorts)/([^/]+)~', $path, $matches)) {
                $videoId = $matches[1];
            }
        }

        return $videoId && preg_match('/^[A-Za-z0-9_-]{6,20}$/', $videoId) ? $videoId : null;
    }

    public function getEmbedUrlAttribute(): ?string
    {
        $videoId = $this->youtube_video_id ?: self::extractYoutubeId($this->youtube_link);

        return $videoId ? 'https://www.youtube.com/embed/' . rawurlencode($videoId) : null;
    }

    public function getDisplayThumbnailAttribute(): string
    {
        if ($this->thumbnail) {
            return asset('uploads/videos/' . $this->thumbnail);
        }

        $videoId = $this->youtube_video_id ?: self::extractYoutubeId($this->youtube_link);
        if ($videoId) {
            return 'https://img.youtube.com/vi/' . rawurlencode($videoId) . '/hqdefault.jpg';
        }

        return asset('assets/images/SalaCode-Logo.png');
    }
}
