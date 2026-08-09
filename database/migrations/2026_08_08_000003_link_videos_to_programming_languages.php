<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('videos', function (Blueprint $table): void {
            $table->foreignId('programming_language_id')
                ->nullable()
                ->after('id')
                ->constrained()
                ->restrictOnDelete();
        });

        DB::table('videos')->orderBy('id')->get()->each(function ($video): void {
            $rawName = trim((string) ($video->playlist_title ?: $video->title));
            $rawSlug = trim((string) $video->playlist_slug);
            $slug = $this->canonicalLanguageSlug($rawSlug ?: Str::slug($rawName), $rawName);
            $name = $this->canonicalLanguageName($slug, $rawName);

            $language = DB::table('programming_languages')->where('slug', $slug)->first();
            if (! $language) {
                $languageId = DB::table('programming_languages')->insertGetId([
                    'name' => $name,
                    'slug' => $slug,
                    'description' => $name . ' video lessons.',
                    'difficulty' => 'Beginner',
                    'estimated_time' => 60,
                    'status' => 'published',
                    'order_number' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                $languageId = $language->id;
            }

            DB::table('videos')->where('id', $video->id)->update([
                'programming_language_id' => $languageId,
                'playlist_title' => $name,
                'playlist_slug' => $slug,
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('videos', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('programming_language_id');
        });
    }

    private function canonicalLanguageSlug(string $slug, string $name): string
    {
        if (str_contains(strtolower($name), 'c++') || str_contains(strtolower($name), 'c plus plus')) {
            return 'cpp';
        }

        return match (strtolower($slug)) {
            'c', 'c-programming' => 'c',
            'c-plus-plus', 'cplusplus', 'cpp', 'c-programming-2' => 'cpp',
            default => $slug ?: 'video-language',
        };
    }

    private function canonicalLanguageName(string $slug, string $fallback): string
    {
        return match ($slug) {
            'c' => 'C Programming',
            'cpp' => 'C++ Programming',
            default => $fallback ?: Str::headline($slug),
        };
    }
};
