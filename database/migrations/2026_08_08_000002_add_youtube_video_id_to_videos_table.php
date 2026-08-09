<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('videos', function (Blueprint $table): void {
            $table->string('youtube_video_id')->nullable()->index()->after('youtube_link');
        });

        DB::table('videos')->whereNotNull('youtube_link')->get()->each(function ($video): void {
            $parts = parse_url($video->youtube_link);
            $host = strtolower($parts['host'] ?? '');
            $path = trim($parts['path'] ?? '', '/');
            $videoId = null;

            if (str_contains($host, 'youtu.be')) {
                $videoId = explode('/', $path)[0] ?? null;
            } elseif (str_contains($host, 'youtube.com')) {
                parse_str($parts['query'] ?? '', $query);
                $videoId = $query['v'] ?? null;
                if (! $videoId && preg_match('~^(?:embed|shorts)/([^/]+)~', $path, $matches)) {
                    $videoId = $matches[1];
                }
            }

            if ($videoId && preg_match('/^[A-Za-z0-9_-]{6,20}$/', $videoId)) {
                DB::table('videos')->where('id', $video->id)->update(['youtube_video_id' => $videoId]);
            }
        });
    }

    public function down(): void
    {
        Schema::table('videos', function (Blueprint $table): void {
            $table->dropIndex(['youtube_video_id']);
            $table->dropColumn('youtube_video_id');
        });
    }
};
