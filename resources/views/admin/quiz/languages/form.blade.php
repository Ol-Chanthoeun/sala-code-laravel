@extends('layouts.admin')

@section('title', $language ? 'Edit Language' : 'Add Language')
@section('page-title', $language ? 'Edit Language' : 'Add Language')
@section('breadcrumb', 'Quiz Languages')

@push('styles')
    <style>
        .logo-upload-zone {
            align-items: center;
            background: #f8fafc;
            border: 2px dashed #cbd5e1;
            border-radius: 10px;
            cursor: pointer;
            display: flex;
            flex-direction: column;
            justify-content: center;
            margin-top: 8px;
            min-height: 132px;
            padding: 16px;
            text-align: center;
            transition: border-color .2s, background-color .2s;
        }
        .logo-upload-zone:hover,
        .logo-upload-zone.is-dragging {
            background: #eef2ff;
            border-color: #4f46e5;
        }
        .logo-upload-zone i { color: #4f46e5; font-size: 28px; margin-bottom: 8px; }
        .logo-upload-zone small { color: #64748b; display: block; margin-top: 5px; }
        .logo-preview { margin-top: 10px; text-align: center; }
        .logo-preview img { border: 1px solid #e2e8f0; border-radius: 8px; height: 90px; max-width: 100%; object-fit: contain; padding: 6px; }
        .logo-file-name { color: #475569; font-size: 13px; margin: 7px 0; overflow-wrap: anywhere; }
        .logo-upload-actions { display: flex; gap: 8px; justify-content: center; }
        .logo-upload-actions button { background: #fff; border: 1px solid #cbd5e1; border-radius: 6px; cursor: pointer; padding: 6px 10px; }
        .logo-upload-actions .remove-logo { color: #dc2626; }
        .logo-upload-error { color: #dc2626; display: none; font-size: 13px; margin-top: 7px; }
    </style>
@endpush

@section('content')
    @if($errors->any())
        <p style="color:#dc2626;margin-bottom:15px;">{{ $errors->first() }}</p>
    @endif

    <div class="system-info">
        <div class="section-title">{{ $language ? 'Edit Programming Language' : 'Create Programming Language' }}</div>
        <form action="{{ $action }}" method="POST" enctype="multipart/form-data">
            @csrf
            @if($method !== 'POST') @method($method) @endif

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:15px;">
                <p><label>Name</label><br><input name="name" value="{{ old('name', $language->name ?? '') }}" required style="width:100%;padding:12px;margin-top:8px;"></p>
                <p><label>Slug</label><br><input name="slug" value="{{ old('slug', $language->slug ?? '') }}" placeholder="auto if blank" style="width:100%;padding:12px;margin-top:8px;"></p>
            </div>

            <p style="margin-top:15px;"><label>Description</label><br><textarea name="description" rows="4" style="width:100%;padding:12px;margin-top:8px;">{{ old('description', $language->description ?? '') }}</textarea></p>

            <div style="display:grid;grid-template-columns:1fr 1fr 1fr 1fr;gap:15px;margin-top:15px;">
                @php
                    $currentLogo = $language?->logo;
                    $currentLogoUrl = $currentLogo
                        ? (Str::startsWith($currentLogo, 'programming-languages/') ? Storage::url($currentLogo) : asset('assets/images/'.$currentLogo))
                        : null;
                @endphp
                <div>
                    <label>Upload Logo</label>
                    <input id="logoInput" type="file" name="logo" accept=".png,.jpg,.jpeg,.svg,.webp,image/png,image/jpeg,image/svg+xml,image/webp" hidden>
                    <input id="removeLogoInput" type="hidden" name="remove_logo" value="0">
                    <label id="logoDropZone" class="logo-upload-zone" for="logoInput">
                        <i class="fas fa-cloud-arrow-up"></i>
                        <strong>Drag & drop an image here</strong>
                        <small>or click to browse · PNG, JPG, SVG, WEBP · Max 2 MB</small>
                    </label>
                    <div id="logoPreview" class="logo-preview" @style(['display:none' => ! $currentLogoUrl])>
                        <img id="logoPreviewImage" src="{{ $currentLogoUrl ?? '' }}" alt="Logo preview">
                        <p id="logoFileName" class="logo-file-name">{{ $currentLogo ? basename($currentLogo) : '' }}</p>
                        <div class="logo-upload-actions">
                            <button id="changeLogo" type="button">Change</button>
                            <button id="removeLogo" class="remove-logo" type="button">Remove</button>
                        </div>
                    </div>
                    <p id="logoUploadError" class="logo-upload-error"></p>
                </div>
                <p><label>Difficulty</label><br><select name="difficulty" required style="width:100%;padding:12px;margin-top:8px;">@foreach(['Beginner','Intermediate','Advanced','Easy','Medium','Hard'] as $value)<option value="{{ $value }}" @selected(old('difficulty', $language->difficulty ?? 'Beginner') === $value)>{{ $value }}</option>@endforeach</select></p>
                <p><label>Estimated time</label><br><input type="number" name="estimated_time" value="{{ old('estimated_time', $language->estimated_time ?? 60) }}" min="1" required style="width:100%;padding:12px;margin-top:8px;"></p>
                <p><label>Order</label><br><input type="number" name="order_number" value="{{ old('order_number', $language->order_number ?? 1) }}" min="1" required style="width:100%;padding:12px;margin-top:8px;"></p>
            </div>

            <p style="margin-top:15px;"><label>Status</label><br><select name="status" required style="width:100%;padding:12px;margin-top:8px;">@foreach(['draft','published','archived'] as $value)<option value="{{ $value }}" @selected(old('status', $language->status ?? 'published') === $value)>{{ ucfirst($value) }}</option>@endforeach</select></p>

            <button type="submit" class="action-btn" style="margin-top:20px;border:none;cursor:pointer;">Save Language</button>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        (() => {
            const input = document.getElementById('logoInput');
            const removeInput = document.getElementById('removeLogoInput');
            const dropZone = document.getElementById('logoDropZone');
            const preview = document.getElementById('logoPreview');
            const previewImage = document.getElementById('logoPreviewImage');
            const fileName = document.getElementById('logoFileName');
            const changeButton = document.getElementById('changeLogo');
            const removeButton = document.getElementById('removeLogo');
            const error = document.getElementById('logoUploadError');
            const allowedExtensions = ['png', 'jpg', 'jpeg', 'svg', 'webp'];
            const maximumSize = 2 * 1024 * 1024;
            let previewUrl = null;

            function clearError() {
                error.textContent = '';
                error.style.display = 'none';
            }

            function showError(message) {
                error.textContent = message;
                error.style.display = 'block';
            }

            function showFile(file) {
                clearError();
                const extension = file.name.split('.').pop().toLowerCase();

                if (!allowedExtensions.includes(extension)) {
                    input.value = '';
                    showError('Please select a PNG, JPG, JPEG, SVG, or WEBP image.');
                    return;
                }

                if (file.size > maximumSize) {
                    input.value = '';
                    showError('The logo must not be larger than 2 MB.');
                    return;
                }

                if (previewUrl) URL.revokeObjectURL(previewUrl);
                previewUrl = URL.createObjectURL(file);
                previewImage.src = previewUrl;
                fileName.textContent = file.name;
                preview.style.display = 'block';
                removeInput.value = '0';
            }

            input.addEventListener('change', () => {
                if (input.files[0]) showFile(input.files[0]);
            });

            ['dragenter', 'dragover'].forEach((eventName) => {
                dropZone.addEventListener(eventName, (event) => {
                    event.preventDefault();
                    dropZone.classList.add('is-dragging');
                });
            });

            ['dragleave', 'drop'].forEach((eventName) => {
                dropZone.addEventListener(eventName, (event) => {
                    event.preventDefault();
                    dropZone.classList.remove('is-dragging');
                });
            });

            dropZone.addEventListener('drop', (event) => {
                const file = event.dataTransfer.files[0];
                if (!file) return;

                const transfer = new DataTransfer();
                transfer.items.add(file);
                input.files = transfer.files;
                showFile(file);
            });

            changeButton.addEventListener('click', () => input.click());
            removeButton.addEventListener('click', () => {
                input.value = '';
                removeInput.value = '1';
                preview.style.display = 'none';
                previewImage.removeAttribute('src');
                fileName.textContent = '';
                clearError();
                if (previewUrl) URL.revokeObjectURL(previewUrl);
                previewUrl = null;
            });
        })();
    </script>
@endpush
