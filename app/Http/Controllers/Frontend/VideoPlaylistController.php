<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Video;
use App\Models\VideoPlaylist;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\View;

class VideoPlaylistController extends Controller
{
    public function index(): View
    {
        $playlists = VideoPlaylist::where('status', 'published')
            ->whereHas('publishedVideos')
            ->with('publishedVideos')
            ->withCount('publishedVideos')
            ->orderBy('order_number')->orderBy('name')->get();

        return view('frontend.videos', compact('playlists'));
    }

    public function playlist(string $playlist): View
    {
        [$record, $videos] = $this->playlistVideos($playlist);
        return view('frontend.video-playlist', ['playlistTitle' => $record->name, 'playlistSlug' => $record->slug, 'videos' => $videos]);
    }

    public function watch(string $playlist, string $video): View
    {
        [$record, $videos] = $this->playlistVideos($playlist);
        $currentIndex = $videos->search(fn (Video $item): bool => $item->slug === $video);
        abort_if($currentIndex === false, 404);

        return view('frontend.video-watch', [
            'playlistTitle' => $record->name, 'playlistSlug' => $record->slug, 'videos' => $videos,
            'currentVideo' => $videos[$currentIndex],
            'previousVideo' => $currentIndex > 0 ? $videos[$currentIndex - 1] : null,
            'nextVideo' => $currentIndex < $videos->count() - 1 ? $videos[$currentIndex + 1] : null,
        ]);
    }

    /** @return array{VideoPlaylist, Collection<int, Video>} */
    private function playlistVideos(string $slug): array
    {
        $playlist = VideoPlaylist::where('slug', $slug)->where('status', 'published')->firstOrFail();
        $videos = $playlist->publishedVideos()->get()->values();
        abort_if($videos->isEmpty(), 404);
        return [$playlist, $videos];
    }
}
