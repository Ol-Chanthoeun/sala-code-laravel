<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\VideoRequest;
use App\Models\Video;
use App\Models\VideoPlaylist;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;

class VideoController extends Controller
{
    public function index(): View
    {
        $query = Video::with('playlist');
        $query->when(request('search'), fn ($builder, $search) => $builder->where(function ($nested) use ($search): void {
            $nested->where('title', 'like', "%{$search}%")->orWhere('description', 'like', "%{$search}%");
        }));
        $query->when(request('playlist'), fn ($builder, $playlist) => $builder->whereHas('playlist', fn ($query) => $query->where('slug', $playlist)));
        $query->when(request('status'), fn ($builder, $status) => $builder->where('status', $status));

        $videos = $query->orderBy('playlist_id')->orderBy('order_number')->orderBy('id')->get();
        $playlistCounts = VideoPlaylist::withCount('videos')->orderBy('order_number')->get();

        return view('admin.videos.index', compact('videos', 'playlistCounts'));
    }

    public function create(): View
    {
        return view('admin.videos.create', ['playlists' => $this->playlists()]);
    }

    public function store(VideoRequest $request): RedirectResponse
    {
        $data = $this->prepareData($request);
        $data['thumbnail'] = $this->storeThumbnail($request);
        Video::create($data);

        return redirect()->route('admin.videos.index')->with('success', 'Video created successfully!');
    }

    public function edit(Video $video): View
    {
        return view('admin.videos.edit', ['video' => $video, 'playlists' => $this->playlists()]);
    }

    public function update(VideoRequest $request, Video $video): RedirectResponse
    {
        $data = $this->prepareData($request, $video);
        $oldThumbnail = $video->thumbnail;
        $data['thumbnail'] = $request->hasFile('thumbnail') ? $this->storeThumbnail($request) : $oldThumbnail;
        $video->update($data);
        if ($request->hasFile('thumbnail')) $this->deleteThumbnail($oldThumbnail);

        return redirect()->route('admin.videos.index')->with('success', 'Video updated successfully!');
    }

    public function destroy(Video $video): RedirectResponse
    {
        $thumbnail = $video->thumbnail;
        $video->delete();
        $this->deleteThumbnail($thumbnail);

        return redirect()->route('admin.videos.index')->with('success', 'Video deleted successfully!');
    }

    public function toggleStatus(Video $video): RedirectResponse
    {
        $video->update(['status' => $video->status === 'published' ? 'draft' : 'published']);

        return redirect()->route('admin.videos.index')->with('success', 'Video status updated successfully!');
    }

    private function prepareData(VideoRequest $request, ?Video $video = null): array
    {
        $data = $request->validated();
        $playlist = VideoPlaylist::findOrFail($data['playlist_id']);
        $data['playlist_title'] = $playlist->name;
        $data['playlist_slug'] = $playlist->slug;
        $data['slug'] = $this->uniqueVideoSlug(
            $playlist->id,
            $data['slug'] ?: $data['title'],
            $video?->id
        );
        $data['youtube_video_id'] = Video::extractYoutubeId($data['youtube_link']);
        unset($data['thumbnail']);

        return $data;
    }

    private function storeThumbnail(VideoRequest $request): ?string
    {
        if (! $request->hasFile('thumbnail')) {
            return null;
        }

        $file = $request->file('thumbnail');
        $name = now()->format('YmdHisv') . '-' . Str::random(8) . '.' . $file->extension();
        File::ensureDirectoryExists(public_path('uploads/videos'));
        $file->move(public_path('uploads/videos'), $name);

        return $name;
    }

    private function deleteThumbnail(?string $name): void
    {
        if ($name) File::delete(public_path('uploads/videos/' . basename($name)));
    }

    private function uniqueVideoSlug(int $playlistId, string $value, ?int $ignoreId = null): string
    {
        $base = Str::slug($value) ?: 'video';
        $slug = $base;
        $suffix = 2;

        while (Video::where('playlist_id', $playlistId)->where('slug', $slug)
            ->when($ignoreId, fn ($query) => $query->whereKeyNot($ignoreId))->exists()) {
            $slug = $base . '-' . $suffix++;
        }

        return $slug;
    }

    private function playlists()
    {
        return VideoPlaylist::orderBy('order_number')->orderBy('name')->get();
    }
}
