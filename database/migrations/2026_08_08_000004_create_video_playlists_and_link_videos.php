<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('video_playlists', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('thumbnail')->nullable();
            $table->string('status', 20)->default('published')->index();
            $table->unsignedInteger('order_number')->default(1);
            $table->timestamps();
        });

        Schema::table('videos', function (Blueprint $table): void {
            $table->foreignId('playlist_id')->nullable()->after('id')->constrained('video_playlists')->cascadeOnDelete();
        });

        DB::table('videos')->orderBy('id')->get()->each(function ($video): void {
            $slug = $this->canonicalSlug((string) $video->playlist_slug, (string) $video->playlist_title);
            $name = $this->canonicalName($slug, (string) $video->playlist_title);
            $playlist = DB::table('video_playlists')->where('slug', $slug)->first();
            $playlistId = $playlist?->id ?: DB::table('video_playlists')->insertGetId([
                'name' => $name,
                'slug' => $slug,
                'description' => $name . ' video lessons.',
                'status' => 'published',
                'order_number' => match ($slug) { 'c' => 1, 'cpp' => 2, 'python' => 3, default => 10 },
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('videos')->where('id', $video->id)->update(['playlist_id' => $playlistId]);
        });
    }

    public function down(): void
    {
        Schema::table('videos', fn (Blueprint $table) => $table->dropConstrainedForeignId('playlist_id'));
        Schema::dropIfExists('video_playlists');
    }

    private function canonicalSlug(string $slug, string $name): string
    {
        if (str_contains(strtolower($name), 'c++')) return 'cpp';
        return match (strtolower($slug)) {
            'c', 'c-programming' => 'c',
            'cpp', 'c-plus-plus', 'cplusplus' => 'cpp',
            'python', 'python-programming' => 'python',
            default => Str::slug($slug ?: $name) ?: 'playlist-' . Str::random(6),
        };
    }

    private function canonicalName(string $slug, string $fallback): string
    {
        return match ($slug) {
            'c' => 'C Programming', 'cpp' => 'C++', 'python' => 'Python',
            default => $fallback ?: Str::headline($slug),
        };
    }
};
