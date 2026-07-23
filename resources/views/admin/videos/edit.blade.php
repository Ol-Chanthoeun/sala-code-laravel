@extends('layouts.admin')

@section('title', 'Edit Video')
@section('page-title', 'Edit Video')
@section('breadcrumb', 'Edit Video')

@section('content')

<div class="system-info">
    <div class="section-title">Edit Video</div>

    <form action="{{ route('admin.videos.update', $video->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <p>
            <label>Video Title</label><br>
            <input type="text" name="title" value="{{ $video->title }}" required
                style="width:100%;padding:12px;margin-top:8px;">
        </p>

        <p style="margin-top:15px;">
            <label>Description</label><br>
            <textarea name="description" rows="5"
                style="width:100%;padding:12px;margin-top:8px;">{{ $video->description }}</textarea>
        </p>

        <p style="margin-top:15px;">
            <label>YouTube Link</label><br>
            <input type="text" name="youtube_link" value="{{ $video->youtube_link }}"
                style="width:100%;padding:12px;margin-top:8px;">
        </p>

        <p style="margin-top:15px;">
            <label>Current Thumbnail</label><br>
            @if($video->thumbnail)
                <img src="{{ asset('uploads/videos/' . $video->thumbnail) }}" width="120">
            @else
                No thumbnail
            @endif
        </p>

        <p style="margin-top:15px;">
            <label>New Thumbnail</label><br>
            <input type="file" name="thumbnail" style="margin-top:8px;">
        </p>

        <button type="submit" class="action-btn" style="margin-top:20px;border:none;cursor:pointer;">
            Update Video
        </button>
    </form>
</div>

@endsection