@extends('layouts.admin')

@section('title', 'Edit Video')
@section('page-title', 'Edit Video')
@section('breadcrumb', 'Edit Video')

@section('content')

<div class="system-info">
    <div class="section-title">Edit Video</div>

    @if($errors->any())<div style="background:#fef2f2;border:1px solid #fecaca;border-radius:8px;color:#b91c1c;margin-bottom:16px;padding:12px;">@foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach</div>@endif

    <form action="{{ route('admin.videos.update', $video->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <p>
            <label>Playlist</label><br>
            <select name="playlist_id" required style="width:100%;padding:12px;margin-top:8px;">
                <option value="">Select a playlist</option>
                @foreach($playlists as $playlist)<option value="{{ $playlist->id }}" @selected((string) old('playlist_id', $video->playlist_id) === (string) $playlist->id)>{{ $playlist->name }}</option>@endforeach
            </select>
        </p>

        <p style="margin-top:15px;">
            <label>Video Title</label><br>
            <input type="text" name="title" value="{{ old('title', $video->title) }}" required
                style="width:100%;padding:12px;margin-top:8px;">
        </p>

        <p style="margin-top:15px;">
            <label>Video Slug</label><br>
            <input type="text" name="slug" value="{{ old('slug', $video->slug) }}" style="width:100%;padding:12px;margin-top:8px;">
        </p>

        <p style="margin-top:15px;">
            <label>Description</label><br>
            <textarea name="description" rows="5"
                style="width:100%;padding:12px;margin-top:8px;">{{ old('description', $video->description) }}</textarea>
        </p>

        <p style="margin-top:15px;">
            <label>YouTube Link</label><br>
            <input type="url" name="youtube_link" value="{{ old('youtube_link', $video->youtube_link) }}" required
                style="width:100%;padding:12px;margin-top:8px;">
        </p>

        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:15px;margin-top:15px;">
            <p><label>Order</label><br><input type="number" name="order_number" value="{{ old('order_number', $video->order_number) }}" min="1" required style="width:100%;padding:12px;margin-top:8px;"></p>
            <p><label>Duration</label><br><input type="text" name="duration" value="{{ old('duration', $video->duration) }}" maxlength="30" style="width:100%;padding:12px;margin-top:8px;"></p>
            <p><label>Status</label><br><select name="status" required style="width:100%;padding:12px;margin-top:8px;"><option value="published" @selected(old('status', $video->status) === 'published')>Published</option><option value="draft" @selected(old('status', $video->status) === 'draft')>Draft</option></select></p>
        </div>

        <p style="margin-top:15px;">
            <label>Current Thumbnail</label><br>
            @if($video->thumbnail)
                <img src="{{ asset('uploads/videos/' . $video->thumbnail) }}" width="120">
            @else
                Using automatic YouTube thumbnail
            @endif
        </p>

        <p style="margin-top:15px;">
            <label>New Optional Custom Thumbnail</label><br>
            <input type="file" name="thumbnail" style="margin-top:8px;">
            <small style="display:block;color:#64748b;margin-top:6px;">Leave blank to keep the current thumbnail behavior.</small>
        </p>

        <button type="submit" class="action-btn" style="margin-top:20px;border:none;cursor:pointer;">
            Update Video
        </button>
    </form>
</div>

@endsection
