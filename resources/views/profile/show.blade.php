@extends('layouts.frontend')

@section('content')
    <section class="profile-page">
        <div class="profile-shell">
            <div class="profile-heading">
                <div>
                    <p class="profile-eyebrow">My Profile</p>
                    <h1>{{ auth()->user()->name }}</h1>
                    <p>{{ auth()->user()->email }}</p>
                </div>
                <span class="profile-badge">{{ ucfirst(str_replace('_', ' ', auth()->user()->role)) }}</span>
            </div>

            @if (session('success'))
                <div class="profile-alert success">{{ session('success') }}</div>
            @endif

            @if ($errors->any())
                <div class="profile-alert error">{{ $errors->first() }}</div>
            @endif

            <div class="profile-grid">
                <form class="profile-panel" action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <h2>Account Information</h2>

                    @if(auth()->user()->isAdmin() || auth()->user()->isSuperAdmin())
                        @php
                            $profileAvatar = auth()->user()->avatar;
                            $profileAvatarUrl = $profileAvatar
                                ? (Str::startsWith($profileAvatar, ['http://', 'https://']) ? $profileAvatar : Storage::url($profileAvatar))
                                : null;
                        @endphp
                        <div class="profile-photo-field">
                            <label for="avatar">Profile photo</label>
                            <div class="profile-photo-row">
                                <div id="profilePhotoPreview" class="profile-photo-preview">
                                    <span id="profilePhotoInitials" @if($profileAvatarUrl) hidden @endif>{{ Str::upper(Str::substr(auth()->user()->name, 0, 2)) }}</span>
                                    @if($profileAvatarUrl)
                                        <img id="profilePhotoImage" src="{{ $profileAvatarUrl }}" alt="Current profile photo" onerror="this.hidden=true;document.getElementById('profilePhotoInitials').hidden=false;">
                                    @else
                                        <img id="profilePhotoImage" src="" alt="Profile photo preview" hidden>
                                    @endif
                                </div>
                                <div class="profile-photo-controls">
                                    <input id="avatar" type="file" name="avatar" accept=".png,.jpg,.jpeg,.webp,image/png,image/jpeg,image/webp" hidden>
                                    <input id="removeAvatarInput" type="hidden" name="remove_avatar" value="0">
                                    <button id="chooseAvatar" class="profile-photo-button" type="button">{{ $profileAvatarUrl ? 'Replace photo' : 'Upload photo' }}</button>
                                    <button id="removeAvatar" class="profile-photo-button remove" type="button" @if(! $profileAvatarUrl) hidden @endif>Remove photo</button>
                                    <small id="avatarFileName">PNG, JPG, JPEG or WEBP · Max 2 MB</small>
                                    <small id="avatarError" class="profile-photo-error"></small>
                                </div>
                            </div>
                        </div>
                    @endif

                    <label for="name">Full name</label>
                    <input id="name" type="text" name="name" value="{{ old('name', auth()->user()->name) }}" required>

                    <label for="email">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email', auth()->user()->email) }}" required>

                    <button type="submit">Save Profile</button>
                </form>

                <form class="profile-panel" action="{{ route('profile.password.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <h2>Change Password</h2>

                    <label for="current_password">Current password</label>
                    <input id="current_password" type="password" name="current_password" required autocomplete="current-password">

                    <label for="password">New password</label>
                    <input id="password" type="password" name="password" required autocomplete="new-password">

                    <label for="password_confirmation">Confirm new password</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password">

                    <button type="submit">Update Password</button>
                </form>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    @if(auth()->user()->isAdmin() || auth()->user()->isSuperAdmin())
        <script>
            (() => {
                const input = document.getElementById('avatar');
                const removeInput = document.getElementById('removeAvatarInput');
                const chooseButton = document.getElementById('chooseAvatar');
                const removeButton = document.getElementById('removeAvatar');
                const image = document.getElementById('profilePhotoImage');
                const initials = document.getElementById('profilePhotoInitials');
                const fileName = document.getElementById('avatarFileName');
                const error = document.getElementById('avatarError');
                const allowedExtensions = ['png', 'jpg', 'jpeg', 'webp'];
                const maximumSize = 2 * 1024 * 1024;
                let previewUrl = null;

                function showError(message) {
                    error.textContent = message;
                }

                input.addEventListener('change', () => {
                    const file = input.files[0];
                    if (!file) return;

                    const extension = file.name.split('.').pop().toLowerCase();
                    if (!allowedExtensions.includes(extension)) {
                        input.value = '';
                        showError('Please select a PNG, JPG, JPEG, or WEBP image.');
                        return;
                    }

                    if (file.size > maximumSize) {
                        input.value = '';
                        showError('The profile photo must not be larger than 2 MB.');
                        return;
                    }

                    if (previewUrl) URL.revokeObjectURL(previewUrl);
                    previewUrl = URL.createObjectURL(file);
                    image.src = previewUrl;
                    image.hidden = false;
                    if (initials) initials.hidden = true;
                    fileName.textContent = file.name;
                    error.textContent = '';
                    removeButton.hidden = false;
                    removeInput.value = '0';
                    chooseButton.textContent = 'Change photo';
                });

                chooseButton.addEventListener('click', () => input.click());
                removeButton.addEventListener('click', () => {
                    input.value = '';
                    removeInput.value = '1';
                    image.hidden = true;
                    image.removeAttribute('src');
                    if (initials) initials.hidden = false;
                    fileName.textContent = 'Photo will be removed after saving.';
                    error.textContent = '';
                    removeButton.hidden = true;
                    chooseButton.textContent = 'Upload photo';
                    if (previewUrl) URL.revokeObjectURL(previewUrl);
                    previewUrl = null;
                });
            })();
        </script>
    @endif
@endpush
