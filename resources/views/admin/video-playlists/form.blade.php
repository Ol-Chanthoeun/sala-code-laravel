@extends('layouts.admin')
@section('title', $playlist ? 'Edit Video Playlist' : 'Add Video Playlist')
@section('page-title', $playlist ? 'Edit Video Playlist' : 'Add Video Playlist')
@section('breadcrumb', 'Video Playlists')

@section('content')
<div class="system-info"><div class="section-title">{{ $playlist ? 'Edit Playlist' : 'Create Playlist' }}</div>
@if($errors->any())<div style="background:#fef2f2;border:1px solid #fecaca;border-radius:8px;color:#b91c1c;margin-bottom:16px;padding:12px;">@foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach</div>@endif
<form action="{{ $action }}" method="POST" enctype="multipart/form-data">@csrf @if($method !== 'POST') @method($method) @endif
<p><label>Name</label><br><input name="name" value="{{ old('name', $playlist?->name) }}" required style="width:100%;padding:12px;margin-top:8px"></p>
<p style="margin-top:15px"><label>Slug</label><br><input name="slug" value="{{ old('slug', $playlist?->slug) }}" placeholder="Generated from name if blank" style="width:100%;padding:12px;margin-top:8px"></p>
<p style="margin-top:15px"><label>Description</label><br><textarea name="description" rows="4" style="width:100%;padding:12px;margin-top:8px">{{ old('description', $playlist?->description) }}</textarea></p>
<div style="display:grid;grid-template-columns:1fr 1fr;gap:15px;margin-top:15px"><p><label>Order</label><input type="number" name="order_number" min="1" value="{{ old('order_number', $playlist?->order_number ?? 1) }}" required style="width:100%;padding:12px;margin-top:8px"></p><p><label>Status</label><select name="status" required style="width:100%;padding:12px;margin-top:8px"><option value="published" @selected(old('status', $playlist?->status ?? 'published') === 'published')>Published</option><option value="draft" @selected(old('status', $playlist?->status) === 'draft')>Draft</option></select></p></div>
<p style="margin-top:15px"><label>Optional Custom Thumbnail</label><br><input id="playlistThumbnailInput" type="file" name="thumbnail" accept="image/png,image/jpeg,image/webp" style="margin-top:8px"><small style="display:block;color:#64748b;margin-top:6px">PNG, JPG/JPEG, or WEBP. If blank, the playlist's programming-language logo is used.</small></p>
<div style="margin-top:12px"><span style="color:#475569;display:block;font-size:13px;font-weight:700;margin-bottom:7px">Thumbnail Preview</span><img id="playlistThumbnailPreview" src="{{ $playlist?->display_thumbnail ?? asset('assets/images/SalaCode-Logo.png') }}" alt="Playlist thumbnail preview" style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:10px;height:120px;object-fit:contain;padding:8px;width:214px"></div>
@if($playlist?->thumbnail)<p style="margin-top:10px"><label><input type="checkbox" name="remove_thumbnail" value="1"> Remove custom thumbnail</label></p>@endif
<button class="action-btn" type="submit" style="border:0;cursor:pointer;margin-top:20px">Save Playlist</button>
</form></div>
@endsection

@push('scripts')
<script>
(() => {
    const input = document.getElementById('playlistThumbnailInput');
    const preview = document.getElementById('playlistThumbnailPreview');
    let objectUrl;
    input?.addEventListener('change', () => {
        if (objectUrl) URL.revokeObjectURL(objectUrl);
        if (!input.files?.[0]) return;
        objectUrl = URL.createObjectURL(input.files[0]);
        preview.src = objectUrl;
    });
    window.addEventListener('pagehide', () => { if (objectUrl) URL.revokeObjectURL(objectUrl); });
})();
</script>
@endpush
