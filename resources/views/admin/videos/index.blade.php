@extends('layouts.admin')

@section('title', 'Videos')
@section('page-title', 'Videos')
@section('breadcrumb', 'Videos')

@section('content')

<a href="{{ route('admin.videos.create') }}" class="action-btn" style="width:180px;margin-bottom:20px;">
    <i class="fas fa-plus-circle"></i> Add Video
</a>

@if(session('success'))
    <p style="color:green;margin-bottom:15px;">{{ session('success') }}</p>
@endif

<div class="data-table">
    <div class="table-header">
        <h3>All Videos</h3>
    </div>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Thumbnail</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>YouTube Link</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                @forelse($videos as $video)
                    <tr>
                        <td>{{ $video->id }}</td>

                        <td>
                            @if($video->thumbnail)
                                <img src="{{ asset('uploads/videos/' . $video->thumbnail) }}" width="80">
                            @else
                                No thumbnail
                            @endif
                        </td>

                        <td>{{ $video->title }}</td>
                        <td>{{ Str::limit($video->description, 50) }}</td>

                        <td>
                            @if($video->youtube_link)
                                <a href="{{ $video->youtube_link }}" target="_blank">Open</a>
                            @else
                                No link
                            @endif
                        </td>

                        <td>
                            <a href="{{ route('admin.videos.edit', $video->id) }}">Edit</a>

                            <form action="{{ route('admin.videos.destroy', $video->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Delete this video?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align:center;">No videos found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection