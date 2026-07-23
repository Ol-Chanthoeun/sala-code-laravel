<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Video;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    public function index()
    {
        $videos = Video::orderBy('id', 'asc')->get();
        return view('admin.videos.index', compact('videos'));
    }

    public function create()
    {
        return view('admin.videos.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'nullable',
            'youtube_link' => 'nullable|string',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $thumbnailName = null;

        if ($request->hasFile('thumbnail')) {
            $thumbnailName = time() . '.' . $request->thumbnail->extension();
            $request->thumbnail->move(public_path('uploads/videos'), $thumbnailName);
        }

        Video::create([
            'title' => $request->title,
            'description' => $request->description,
            'youtube_link' => $request->youtube_link,
            'thumbnail' => $thumbnailName,
        ]);

        return redirect()->route('admin.videos.index')
            ->with('success', 'Video created successfully!');
    }

    public function edit(Video $video)
    {
        return view('admin.videos.edit', compact('video'));
    }

    public function update(Request $request, Video $video)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'nullable',
            'youtube_link' => 'nullable|string',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $thumbnailName = $video->thumbnail;

        if ($request->hasFile('thumbnail')) {
            $thumbnailName = time() . '.' . $request->thumbnail->extension();
            $request->thumbnail->move(public_path('uploads/videos'), $thumbnailName);
        }

        $video->update([
            'title' => $request->title,
            'description' => $request->description,
            'youtube_link' => $request->youtube_link,
            'thumbnail' => $thumbnailName,
        ]);

        return redirect()->route('admin.videos.index')
            ->with('success', 'Video updated successfully!');
    }

    public function destroy(Video $video)
    {
        $video->delete();

        return redirect()->route('admin.videos.index')
            ->with('success', 'Video deleted successfully!');
    }
}