@extends('layouts.admin')
@section('title', 'Video Playlists')
@section('page-title', 'Video Playlists')
@section('breadcrumb', 'Video Playlists')

@section('content')
<div class="admin-sticky-toolbar" style="display:flex;gap:10px;padding-bottom:16px;">
    <a class="action-btn admin-primary-action admin-modal-form-link" data-modal-title="Add Video Playlist" href="{{ route('admin.video-playlists.create', ['modal' => 1]) }}"><i class="fas fa-plus-circle"></i> Add Playlist</a>
    <a class="action-btn" href="{{ route('admin.videos.index') }}"><i class="fas fa-video"></i> Videos</a>
</div>
@if(session('success'))<p style="color:green;margin-bottom:15px;">{{ session('success') }}</p>@endif
<div class="data-table"><div class="table-header"><h3>All Video Playlists</h3></div><div class="table-responsive">
<table><thead><tr><th>Thumbnail</th><th>Name</th><th>Slug</th><th>Published</th><th>Order</th><th>Status</th><th>Actions</th></tr></thead><tbody>
@forelse($playlists as $playlist)
<tr>
    <td><img src="{{ $playlist->display_thumbnail }}" width="90" height="52" style="border-radius:7px;object-fit:cover" alt="{{ $playlist->name }}"></td>
    <td>{{ $playlist->name }}</td><td>{{ $playlist->slug }}</td><td>{{ $playlist->published_videos_count }} / {{ $playlist->videos_count }}</td><td>{{ $playlist->order_number }}</td><td>{{ ucfirst($playlist->status) }}</td>
    <td><div class="admin-table-actions">
        <a class="admin-icon-btn admin-icon-btn--primary" href="{{ route('videos.playlist', $playlist) }}" target="_blank" title="View"><i class="fas fa-eye"></i></a>
        <a class="admin-icon-btn admin-icon-btn--edit admin-modal-form-link" data-modal-title="Edit Video Playlist" href="{{ route('admin.video-playlists.edit', ['video_playlist' => $playlist, 'modal' => 1]) }}" title="Edit"><i class="fas fa-pen"></i></a>
        <form class="admin-destructive-form" action="{{ route('admin.video-playlists.destroy', $playlist) }}" method="POST" data-confirm-title="Delete Video Playlist" data-confirm-message="This permanently deletes the playlist and all videos assigned to it." data-confirm-item="{{ $playlist->name }}" data-confirm-label="Delete Playlist">@csrf @method('DELETE')<button class="admin-icon-btn admin-icon-btn--danger" type="submit" title="Delete"><i class="fas fa-trash"></i></button></form>
    </div></td>
</tr>
@empty<tr><td colspan="7" style="text-align:center">No video playlists found.</td></tr>@endforelse
</tbody></table></div></div>
@endsection
