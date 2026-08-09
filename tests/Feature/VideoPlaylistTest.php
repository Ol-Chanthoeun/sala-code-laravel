<?php

namespace Tests\Feature;

use App\Models\Video;
use App\Models\VideoPlaylist;
use App\Models\User;
use Database\Seeders\CProgrammingVideoSeeder;
use Database\Seeders\CppPythonVideoSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VideoPlaylistTest extends TestCase
{
    use RefreshDatabase;

    public function test_playlist_only_displays_published_videos_for_selected_language_in_order(): void
    {
        $c = $this->playlist('C Programming', 'c');
        $cpp = $this->playlist('C++', 'cpp');

        $this->video($c, 'Second C Lesson', 'second-c-lesson', 2, 'published', 'rLf3jnHxSmU');
        $this->video($c, 'First C Lesson', 'first-c-lesson', 1, 'published', 'ZSZwDARaQYI');
        $this->video($c, 'Draft C Lesson', 'draft-c-lesson', 3, 'draft', 'fO4FwJOShdc');
        $this->video($cpp, 'C++ Lesson', 'cpp-lesson', 1, 'published', '9-BjXs1vMSc');

        $this->get(route('videos.playlist', 'c'))
            ->assertOk()
            ->assertSeeTextInOrder(['First C Lesson', 'Second C Lesson'])
            ->assertDontSeeText('Draft C Lesson')
            ->assertDontSeeText('C++ Lesson');
    }

    public function test_watch_page_cannot_open_a_video_from_another_language(): void
    {
        $c = $this->playlist('C Programming', 'c');
        $cpp = $this->playlist('C++', 'cpp');
        $this->video($c, 'C Lesson', 'c-lesson', 1, 'published', 'rLf3jnHxSmU');
        $this->video($cpp, 'C++ Lesson', 'cpp-lesson', 1, 'published', '9-BjXs1vMSc');

        $this->get(route('videos.watch', ['c', 'cpp-lesson']))->assertNotFound();
        $this->get(route('videos.watch', ['c', 'c-lesson']))
            ->assertOk()
            ->assertSee('https://www.youtube.com/embed/rLf3jnHxSmU', false);
    }

    public function test_real_course_seeders_create_expected_published_playlist_counts(): void
    {
        $this->seed(CProgrammingVideoSeeder::class);
        $this->seed(CppPythonVideoSeeder::class);

        $this->assertSame(18, VideoPlaylist::where('slug', 'c')->firstOrFail()->publishedVideos()->count());
        $this->assertSame(18, VideoPlaylist::where('slug', 'cpp')->firstOrFail()->publishedVideos()->count());
        $this->assertSame(20, VideoPlaylist::where('slug', 'python')->firstOrFail()->publishedVideos()->count());
        $this->assertSame('rLf3jnHxSmU', VideoPlaylist::where('slug', 'c')->firstOrFail()->publishedVideos()->first()->youtube_video_id);
    }

    public function test_admin_can_manage_video_playlists(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);

        $this->actingAs($admin)->post(route('admin.video-playlists.store'), [
            'name' => 'Java', 'slug' => 'java', 'description' => 'Java videos',
            'status' => 'published', 'order_number' => 4,
        ])->assertRedirect(route('admin.video-playlists.index'));

        $this->assertDatabaseHas('video_playlists', ['name' => 'Java', 'slug' => 'java', 'status' => 'published']);
    }

    public function test_playlist_cards_use_language_logos_unless_admin_uploads_a_thumbnail(): void
    {
        $c = $this->playlist('C Programming', 'c');
        $cpp = $this->playlist('C++', 'cpp');
        $python = $this->playlist('Python', 'python');

        $this->assertStringEndsWith('/assets/images/video-playlists/c.svg', $c->display_thumbnail);
        $this->assertStringEndsWith('/assets/images/video-playlists/cpp.svg', $cpp->display_thumbnail);
        $this->assertStringEndsWith('/assets/images/video-playlists/python.svg', $python->display_thumbnail);

        $python->update(['thumbnail' => 'custom-python.webp']);
        $this->assertStringEndsWith('/uploads/video-playlists/custom-python.webp', $python->fresh()->display_thumbnail);
    }

    private function playlist(string $name, string $slug): VideoPlaylist
    {
        return VideoPlaylist::create(['name' => $name, 'slug' => $slug, 'status' => 'published', 'order_number' => 1]);
    }

    private function video(VideoPlaylist $playlist, string $title, string $slug, int $order, string $status, string $youtubeId): Video
    {
        return Video::create([
            'playlist_id' => $playlist->id,
            'playlist_title' => $playlist->name,
            'playlist_slug' => $playlist->slug,
            'title' => $title,
            'slug' => $slug,
            'order_number' => $order,
            'status' => $status,
            'youtube_link' => 'https://www.youtube.com/watch?v=' . $youtubeId,
            'youtube_video_id' => $youtubeId,
        ]);
    }
}
