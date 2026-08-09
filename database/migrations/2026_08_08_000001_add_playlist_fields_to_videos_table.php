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
            $table->string('playlist_title')->nullable()->after('id');
            $table->string('playlist_slug')->nullable()->index()->after('playlist_title');
            $table->string('slug')->nullable()->after('title');
            $table->unsignedInteger('order_number')->default(1)->after('description');
            $table->string('duration', 30)->nullable()->after('order_number');
            $table->string('status', 20)->default('published')->after('duration');
        });

        DB::table('videos')->orderBy('id')->get()->each(function ($video): void {
            DB::table('videos')->where('id', $video->id)->update([
                'playlist_title' => $video->title,
                'playlist_slug' => Str::slug($video->title) ?: 'playlist-' . $video->id,
                'slug' => Str::slug($video->title) ?: 'video-' . $video->id,
                'order_number' => 1,
                'status' => 'published',
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('videos', function (Blueprint $table): void {
            $table->dropIndex(['playlist_slug']);
            $table->dropColumn(['playlist_title', 'playlist_slug', 'slug', 'order_number', 'duration', 'status']);
        });
    }
};
