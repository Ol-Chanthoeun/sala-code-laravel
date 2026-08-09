<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\VideoPlaylistRequest;
use App\Models\VideoPlaylist;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\View\View;

class VideoPlaylistController extends Controller
{
    public function index(): View
    {
        $playlists = VideoPlaylist::withCount(['videos', 'publishedVideos'])->orderBy('order_number')->orderBy('name')->get();
        return view('admin.video-playlists.index', compact('playlists'));
    }

    public function create(): View
    {
        return view('admin.video-playlists.form', ['playlist' => null, 'action' => route('admin.video-playlists.store'), 'method' => 'POST']);
    }

    public function store(VideoPlaylistRequest $request): RedirectResponse
    {
        $data = $this->data($request);
        $data['thumbnail'] = $this->storeThumbnail($request);
        VideoPlaylist::create($data);
        return redirect()->route('admin.video-playlists.index')->with('success', 'Video playlist created.');
    }

    public function edit(VideoPlaylist $videoPlaylist): View
    {
        return view('admin.video-playlists.form', ['playlist' => $videoPlaylist, 'action' => route('admin.video-playlists.update', $videoPlaylist), 'method' => 'PUT']);
    }

    public function update(VideoPlaylistRequest $request, VideoPlaylist $videoPlaylist): RedirectResponse
    {
        $data = $this->data($request, $videoPlaylist);
        $old = $videoPlaylist->thumbnail;
        if ($request->hasFile('thumbnail')) $data['thumbnail'] = $this->storeThumbnail($request);
        elseif ($request->boolean('remove_thumbnail')) $data['thumbnail'] = null;
        $videoPlaylist->update($data);
        if (($request->hasFile('thumbnail') || $request->boolean('remove_thumbnail')) && $old) $this->deleteThumbnail($old);
        return redirect()->route('admin.video-playlists.index')->with('success', 'Video playlist updated.');
    }

    public function destroy(VideoPlaylist $videoPlaylist): RedirectResponse
    {
        $thumbnail = $videoPlaylist->thumbnail;
        $videoPlaylist->delete();
        $this->deleteThumbnail($thumbnail);
        return redirect()->route('admin.video-playlists.index')->with('success', 'Video playlist and its videos deleted.');
    }

    private function data(VideoPlaylistRequest $request, ?VideoPlaylist $playlist = null): array
    {
        $data = $request->validated();
        $data['slug'] = Str::slug($data['slug'] ?: $data['name']) ?: ($playlist?->slug ?? 'playlist');
        unset($data['thumbnail'], $data['remove_thumbnail']);
        return $data;
    }

    private function storeThumbnail(VideoPlaylistRequest $request): ?string
    {
        if (! $request->hasFile('thumbnail')) return null;
        $file = $request->file('thumbnail');
        $name = now()->format('YmdHisv') . '-' . Str::random(8) . '.' . $file->extension();
        File::ensureDirectoryExists(public_path('uploads/video-playlists'));
        $file->move(public_path('uploads/video-playlists'), $name);
        return $name;
    }

    private function deleteThumbnail(?string $name): void
    {
        if ($name) File::delete(public_path('uploads/video-playlists/' . basename($name)));
    }
}
