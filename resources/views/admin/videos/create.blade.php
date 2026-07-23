@extends('layouts.admin')

@section('title', 'Add Video')
@section('page-title', 'Add Video')
@section('breadcrumb', 'Add Video')

@section('content')

<div class="system-info">
    <div class="section-title">Create New Video</div>

    <form action="{{ route('admin.videos.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <p>
            <label>Video Title</label><br>
            <input type="text" name="title" required style="width:100%;padding:12px;margin-top:8px;">
        </p>

        <p style="margin-top:15px;">
            <label>Description</label><br>
            <textarea name="description" rows="5" style="width:100%;padding:12px;margin-top:8px;"></textarea>
        </p>

        <p style="margin-top:15px;">
            <label>YouTube Link</label><br>
            <input type="text" name="youtube_link" style="width:100%;padding:12px;margin-top:8px;">
        </p>

        <p style="margin-top:15px;">
            <label>Thumbnail</label><br>
            <input type="file" name="thumbnail" style="margin-top:8px;">
        </p>

        <button type="submit" class="action-btn" style="margin-top:20px;border:none;cursor:pointer;">
            Save Video
        </button>
    </form>
</div>

@endsection