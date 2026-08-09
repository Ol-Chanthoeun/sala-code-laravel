@extends('layouts.admin')

@section('title', 'Videos')
@section('page-title', 'Videos')
@section('breadcrumb', 'Videos')

@section('content')

<div class="admin-sticky-toolbar" style="padding-bottom:16px;">
    <div style="display:flex;align-items:center;gap:12px;flex-wrap:wrap;margin-bottom:12px;">
        <a href="{{ route('admin.videos.create', ['modal' => 1]) }}" class="action-btn admin-primary-action admin-modal-form-link" data-modal-title="Add Video" style="width:180px;">
            <i class="fas fa-plus-circle"></i> Add Video
        </a>
        <a href="{{ route('admin.video-playlists.index') }}" class="action-btn" style="width:180px;"><i class="fas fa-list"></i> Playlists</a>
        @php($selectedPlaylist = $playlistCounts->firstWhere('slug', request('playlist')))
        @include('admin.reports._quick-export', ['reportType' => 'videos', 'reportQuery' => ['playlist_id' => $selectedPlaylist?->id], 'reportExcept' => ['playlist']])
        @foreach($playlistCounts as $playlist)
            <span style="background:#fff;border:1px solid #e2e8f0;border-radius:999px;color:#475569;font-size:12px;padding:7px 10px;">{{ $playlist->name }} — {{ $playlist->videos_count }} videos</span>
        @endforeach
    </div>
    <form method="GET" action="{{ route('admin.videos.index') }}" style="display:grid;grid-template-columns:minmax(220px,2fr) minmax(170px,1fr) minmax(150px,1fr) auto auto;gap:10px;align-items:end;">
        <label style="font-size:13px;font-weight:700;">Search Video<input name="search" value="{{ request('search') }}" placeholder="Title or description" style="width:100%;padding:11px;margin-top:6px;border:1px solid #cbd5e1;border-radius:8px;"></label>
        <label style="font-size:13px;font-weight:700;">Playlist<select name="playlist" style="width:100%;padding:11px;margin-top:6px;border:1px solid #cbd5e1;border-radius:8px;"><option value="">All playlists</option>@foreach($playlistCounts as $playlist)<option value="{{ $playlist->slug }}" @selected(request('playlist') === $playlist->slug)>{{ $playlist->name }}</option>@endforeach</select></label>
        <label style="font-size:13px;font-weight:700;">Status<select name="status" style="width:100%;padding:11px;margin-top:6px;border:1px solid #cbd5e1;border-radius:8px;"><option value="">All statuses</option><option value="published" @selected(request('status') === 'published')>Published</option><option value="draft" @selected(request('status') === 'draft')>Draft</option></select></label>
        <button class="action-btn" type="submit" style="border:0;cursor:pointer;padding:11px 16px;"><i class="fas fa-filter"></i> Filter</button>
        <a href="{{ route('admin.videos.index') }}" style="background:#64748b;border-radius:8px;color:#fff;padding:11px 16px;text-align:center;text-decoration:none;">Reset</a>
    </form>
</div>

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
                    <th>Thumbnail</th>
                    <th>Title</th>
                    <th>Playlist / Language</th>
                    <th>Duration</th>
                    <th>Order</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
                @forelse($videos as $video)
                    <tr>
                        <td>
                            <img src="{{ $video->display_thumbnail }}" width="80" height="48" style="border-radius:7px;object-fit:cover;" alt="{{ $video->title }}">
                        </td>

                        <td>{{ $video->title }}</td>
                        <td>{{ $video->playlist->name }}</td>
                        <td>{{ $video->duration ?: '—' }}</td>
                        <td>{{ $video->order_number }}</td>
                        <td>{{ ucfirst($video->status) }}</td>

                        <td>
                            <div class="admin-table-actions">
                                <button class="admin-icon-btn admin-icon-btn--primary view-video-trigger" type="button" title="View Video" aria-label="View Video" data-video-title="{{ $video->title }}" data-video-playlist="{{ $video->playlist->name }}" data-video-description="{{ $video->description }}" data-video-duration="{{ $video->duration ?: '—' }}" data-video-order="{{ $video->order_number }}" data-video-status="{{ ucfirst($video->status) }}" data-video-thumbnail="{{ $video->display_thumbnail }}" data-video-id="{{ $video->youtube_video_id }}"><i class="fas fa-eye" aria-hidden="true"></i></button>
                                <a class="admin-icon-btn admin-icon-btn--edit admin-modal-form-link" href="{{ route('admin.videos.edit', ['video' => $video->id, 'modal' => 1]) }}" data-modal-title="Edit Video" title="Edit Video" aria-label="Edit Video"><i class="fas fa-pen" aria-hidden="true"></i></a>
                                <form class="admin-destructive-form" action="{{ route('admin.videos.toggle-status', $video) }}" method="POST" data-confirm-title="Confirm {{ $video->status === 'published' ? 'Unpublish' : 'Publish' }} Video" data-confirm-message="Are you sure you want to {{ $video->status === 'published' ? 'hide this video from' : 'publish this video to' }} the public playlist?" data-confirm-item="{{ $video->title }}" data-confirm-label="{{ $video->status === 'published' ? 'Unpublish' : 'Publish' }}">@csrf @method('PATCH')<button class="admin-icon-btn {{ $video->status === 'published' ? 'admin-icon-btn--warning' : 'admin-icon-btn--success' }}" type="submit" title="{{ $video->status === 'published' ? 'Unpublish' : 'Publish' }} Video" aria-label="{{ $video->status === 'published' ? 'Unpublish' : 'Publish' }} Video"><i class="fas {{ $video->status === 'published' ? 'fa-eye-slash' : 'fa-circle-check' }}" aria-hidden="true"></i></button></form>
                                <form class="admin-destructive-form" action="{{ route('admin.videos.destroy', $video->id) }}" method="POST" data-confirm-title="Delete Video" data-confirm-message="This video will disappear from the public playlist. Are you sure you want to permanently delete it?" data-confirm-item="{{ $video->title }}" data-confirm-label="Delete Video">
                                    @csrf
                                    @method('DELETE')
                                    <button class="admin-icon-btn admin-icon-btn--danger" type="submit" title="Delete Video" aria-label="Delete Video"><i class="fas fa-trash" aria-hidden="true"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align:center;">No videos found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<dialog class="admin-confirm-dialog" id="videoDetailsDialog" aria-labelledby="videoDetailsTitle">
    <div class="admin-confirm-dialog__panel admin-details-modal__panel">
        <div class="admin-confirm-dialog__header admin-details-modal__header"><div class="admin-confirm-dialog__title"><i class="fas fa-circle-play"></i><h3 id="videoDetailsTitle">Video Details</h3></div><button class="admin-confirm-dialog__close" id="closeVideoDetails" type="button" aria-label="Close">&times;</button></div>
        <div style="overflow-y:auto;padding:18px 22px 20px;">
            <img id="videoDetailsThumbnail" style="border-radius:10px;height:150px;object-fit:cover;width:100%;" alt="">
            <h4 id="videoDetailsName" style="font-size:21px;margin:15px 0 10px;"></h4>
            <p id="videoDetailsDescription" style="color:#64748b;line-height:1.6;margin-bottom:12px;"></p>
            <dl class="admin-details-modal__grid"><div><dt>Playlist</dt><dd id="videoDetailsPlaylist"></dd></div><div><dt>Duration</dt><dd id="videoDetailsDuration"></dd></div><div><dt>Order</dt><dd id="videoDetailsOrder"></dd></div><div><dt>Status</dt><dd id="videoDetailsStatus"></dd></div><div class="admin-details-modal__wide"><dt>YouTube Video ID</dt><dd id="videoDetailsYoutubeId"></dd></div></dl>
        </div>
        <div class="admin-confirm-dialog__actions admin-details-modal__footer"><button class="admin-confirm-dialog__cancel" id="cancelVideoDetails" type="button">Close</button></div>
    </div>
</dialog>

@endsection

@push('scripts')
<script>
(() => {
    const dialog = document.getElementById('videoDetailsDialog');
    document.querySelectorAll('.view-video-trigger').forEach((button) => button.addEventListener('click', () => {
        const value = (name) => button.dataset[name] || '—';
        document.getElementById('videoDetailsThumbnail').src = button.dataset.videoThumbnail;
        document.getElementById('videoDetailsThumbnail').alt = button.dataset.videoTitle;
        document.getElementById('videoDetailsName').textContent = value('videoTitle');
        document.getElementById('videoDetailsDescription').textContent = value('videoDescription');
        document.getElementById('videoDetailsPlaylist').textContent = value('videoPlaylist');
        document.getElementById('videoDetailsDuration').textContent = value('videoDuration');
        document.getElementById('videoDetailsOrder').textContent = value('videoOrder');
        document.getElementById('videoDetailsStatus').textContent = value('videoStatus');
        document.getElementById('videoDetailsYoutubeId').textContent = value('videoId');
        dialog.showModal(); document.body.classList.add('admin-modal-open');
    }));
    document.getElementById('closeVideoDetails').onclick = document.getElementById('cancelVideoDetails').onclick = () => dialog.close();
    dialog.addEventListener('click', (event) => { if (event.target === dialog) dialog.close(); });
    dialog.addEventListener('close', () => document.body.classList.remove('admin-modal-open'));
})();
</script>
@endpush
