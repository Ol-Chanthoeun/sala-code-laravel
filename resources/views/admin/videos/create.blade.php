@extends('layouts.admin')

@section('title', 'Add Video')
@section('page-title', 'Add Video')
@section('breadcrumb', 'Add Video')

@section('content')

<div class="system-info">
    <div class="section-title">Create New Video</div>

    @if($errors->any())<div style="background:#fef2f2;border:1px solid #fecaca;border-radius:8px;color:#b91c1c;margin-bottom:16px;padding:12px;">@foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach</div>@endif

    <form action="{{ route('admin.videos.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <p>
            <label>Playlist</label><br>
            <select name="playlist_id" required style="width:100%;padding:12px;margin-top:8px;">
                <option value="">Select a playlist</option>
                @foreach($playlists as $playlist)<option value="{{ $playlist->id }}" @selected((string) old('playlist_id') === (string) $playlist->id)>{{ $playlist->name }}</option>@endforeach
            </select>
        </p>

        <p style="margin-top:15px;">
            <label>Video Title</label><br>
            <input type="text" name="title" value="{{ old('title') }}" required style="width:100%;padding:12px;margin-top:8px;">
        </p>

        <p style="margin-top:15px;">
            <label>Video Slug</label><br>
            <input type="text" name="slug" value="{{ old('slug') }}" placeholder="Auto-generated if blank" style="width:100%;padding:12px;margin-top:8px;">
        </p>

        <p style="margin-top:15px;">
            <label>Description</label><br>
            <textarea name="description" rows="5" style="width:100%;padding:12px;margin-top:8px;">{{ old('description') }}</textarea>
        </p>

        <p style="margin-top:15px;">
            <label>YouTube Link</label><br>
            <input type="url" name="youtube_link" value="{{ old('youtube_link') }}" required placeholder="https://www.youtube.com/watch?v=..." style="width:100%;padding:12px;margin-top:8px;">
        </p>

        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:15px;margin-top:15px;">
            <p><label>Order</label><br><input type="number" name="order_number" value="{{ old('order_number', 1) }}" min="1" required style="width:100%;padding:12px;margin-top:8px;"></p>
            <p><label>Duration</label><br><input type="text" name="duration" value="{{ old('duration') }}" placeholder="e.g. 12:30" maxlength="30" style="width:100%;padding:12px;margin-top:8px;"></p>
            <p><label>Status</label><br><select name="status" required style="width:100%;padding:12px;margin-top:8px;"><option value="published" @selected(old('status', 'published') === 'published')>Published</option><option value="draft" @selected(old('status') === 'draft')>Draft</option></select></p>
        </div>

        <p style="margin-top:15px;">
            <label>Optional Custom Thumbnail</label><br>
            <input type="file" name="thumbnail" style="margin-top:8px;">
            <small style="display:block;color:#64748b;margin-top:6px;">Leave blank to use the YouTube thumbnail automatically.</small>
        </p>

        <button type="submit" class="action-btn" style="margin-top:20px;border:none;cursor:pointer;">
            Save Video
        </button>
    </form>
</div>

@endsection
