@extends('layouts.frontend')

@php
    $profileUser = auth()->user();
    $profileAvatar = $profileUser->avatar;
    $profileAvatarUrl = $profileAvatar
        ? (Str::startsWith($profileAvatar, ['http://', 'https://']) ? $profileAvatar : Storage::url($profileAvatar))
        : null;
    $profileInitials = Str::upper(Str::substr($profileUser->name, 0, 2));
    $displayName = preg_match("/^[\p{Latin}\s'’-]+$/u", $profileUser->name)
        ? Str::title(Str::lower($profileUser->name))
        : $profileUser->name;
    $displayRole = Str::headline($profileUser->role);
@endphp

@push('styles')
    <style>
        .profile-heading__identity { align-items: center; display: flex; flex-wrap: wrap; gap: 9px; }
        .profile-heading__identity h1 { margin-bottom: 0; }
        .profile-heading .profile-badge { border-radius: 999px; font-size: 12px; line-height: 1; padding: 6px 10px; }

        .profile-grid { align-items: stretch; }
        .profile-panel { display: flex; flex-direction: column; height: 100%; }
        .profile-panel__submit { margin-top: auto !important; width: 100%; }

        .profile-photo-field { margin-bottom: 8px; }
        .profile-photo-field > label { margin-top: 0; }
        .profile-photo-preview { border: 3px solid #fff; box-shadow: 0 0 0 1px #dbe4f0, 0 8px 18px rgba(15, 23, 42, .12); position: relative; }
        .profile-photo-edit {
            align-items: center;
            background: #1f6fe5 !important;
            border: 2px solid #fff !important;
            border-radius: 50% !important;
            bottom: -2px;
            box-shadow: 0 3px 8px rgba(15, 23, 42, .2);
            display: inline-flex;
            height: 30px;
            justify-content: center;
            margin: 0 !important;
            padding: 0 !important;
            position: absolute;
            right: -2px;
            width: 30px;
        }
        .profile-photo-edit i { font-size: 12px; }
        .profile-panel .profile-photo-button { border-radius: 7px; font-size: 13px; padding: 8px 11px; }

        .profile-account-status {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            display: grid;
            gap: 8px;
            margin: 18px 0;
            padding: 12px 14px;
        }
        .profile-account-status div { align-items: center; display: flex; font-size: 13px; justify-content: space-between; }
        .profile-account-status dt { color: #64748b; }
        .profile-account-status dd { color: #1e293b; font-weight: 700; margin: 0; }
        .profile-account-status .status-active { color: #15803d; }
        .profile-account-status .status-pending { color: #b45309; }

        .profile-panel input {
            background: #fff;
            border: 1px solid #d6dce8;
            border-radius: 8px;
            min-height: 48px;
            outline: none;
            transition: border-color .18s ease, box-shadow .18s ease;
        }
        .profile-panel input:hover { border-color: #aeb9ca; }
        .profile-panel input:focus { border-color: #2876e8; box-shadow: 0 0 0 3px rgba(40, 118, 232, .13); }
        .profile-panel input.is-invalid { border-color: #dc2626; }
        .profile-panel input.is-invalid:focus { box-shadow: 0 0 0 3px rgba(220, 38, 38, .1); }
        .profile-field-error { color: #b91c1c; font-size: 13px; margin-top: 6px; }

        .profile-password-field { position: relative; }
        .profile-password-field input { padding-right: 48px; }
        .profile-password-toggle {
            align-items: center;
            background: transparent !important;
            border: 0 !important;
            border-radius: 6px !important;
            color: #64748b !important;
            display: inline-flex;
            height: 36px;
            justify-content: center;
            margin: 0 !important;
            padding: 0 !important;
            position: absolute;
            right: 6px;
            top: 6px;
            width: 36px;
        }
        .profile-password-toggle:hover { color: #2876e8 !important; }
        .profile-password-toggle:focus-visible { box-shadow: 0 0 0 3px rgba(40, 118, 232, .14); outline: none; }
        .profile-password-helper { color: #64748b; font-size: 12px; line-height: 1.4; margin-top: 6px; }

        @media (max-width: 768px) {
            .profile-page {
                padding: 84px 16px 36px;
            }
            .profile-heading {
                gap: 0;
                margin-bottom: 20px;
            }
            .profile-eyebrow {
                font-size: 13px;
                margin-bottom: 7px;
            }
            .profile-heading__identity { gap: 7px; }
            .profile-heading h1 {
                font-size: 26px;
                line-height: 1.2;
            }
            .profile-heading > div > p:last-child {
                font-size: 14px;
                margin-top: 5px;
            }
            .profile-grid { gap: 16px; }
            .profile-panel {
                min-width: 0;
                padding: 18px;
                width: 100%;
            }
            .profile-panel h2 { margin-bottom: 12px; }
            .profile-panel label { margin: 12px 0 5px; }
            .profile-photo-field { margin-bottom: 4px; }
            .profile-photo-row {
                align-items: center;
                gap: 12px;
            }
            .profile-photo-preview {
                flex-basis: 76px;
                height: 76px;
                width: 76px;
            }
            .profile-photo-controls { gap: 6px; }
            .profile-panel .profile-photo-button {
                font-size: 12px;
                padding: 7px 9px;
            }
            .profile-photo-controls small {
                font-size: 11px;
                line-height: 1.35;
            }
            .profile-account-status { margin: 14px 0; }
            .profile-password-helper { margin-top: 4px; }
        }

        @media (max-width: 430px) {
            .profile-photo-row { flex-direction: row; }
        }
    </style>
@endpush

@section('content')
    <section class="profile-page">
        <div class="profile-shell">
            <div class="profile-heading">
                <div>
                    <p class="profile-eyebrow">My Profile</p>
                    <div class="profile-heading__identity">
                        <h1>{{ $displayName }}</h1>
                        <span class="profile-badge">{{ $displayRole }}</span>
                    </div>
                    <p>{{ $profileUser->email }}</p>
                </div>
            </div>

            @if (session('success'))
                <div class="profile-alert success">{{ session('success') }}</div>
            @endif

            @if ($errors->any())
                <div class="profile-alert error">Please review the highlighted fields below.</div>
            @endif

            <div class="profile-grid">
                <form class="profile-panel" action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <h2>Account Information</h2>

                    <div class="profile-photo-field">
                        <label for="avatar">Profile photo</label>
                        <div class="profile-photo-row">
                            <div id="profilePhotoPreview" class="profile-photo-preview">
                                <span id="profilePhotoInitials" @if($profileAvatarUrl) hidden @endif>{{ $profileInitials }}</span>
                                @if($profileAvatarUrl)
                                    <img id="profilePhotoImage" src="{{ $profileAvatarUrl }}" alt="Current profile photo" onerror="this.hidden=true;document.getElementById('profilePhotoInitials').hidden=false;">
                                @else
                                    <img id="profilePhotoImage" src="" alt="Profile photo preview" hidden>
                                @endif
                                <button id="avatarEditButton" class="profile-photo-edit" type="button" aria-label="Change profile photo"><i class="fa-solid fa-camera" aria-hidden="true"></i></button>
                            </div>
                            <div class="profile-photo-controls">
                                <input id="avatar" type="file" name="avatar" accept=".png,.jpg,.jpeg,.webp,image/png,image/jpeg,image/webp" hidden>
                                <input id="removeAvatarInput" type="hidden" name="remove_avatar" value="0">
                                <button id="chooseAvatar" class="profile-photo-button" type="button">Change Photo</button>
                                <button id="removeAvatar" class="profile-photo-button remove" type="button" @if(! $profileAvatarUrl) hidden @endif>Remove</button>
                                <small id="avatarFileName">PNG, JPG, JPEG or WEBP · Max 2 MB</small>
                                <small id="avatarError" class="profile-photo-error">@error('avatar'){{ $message }}@enderror</small>
                            </div>
                        </div>
                    </div>

                    <label for="name">Full name</label>
                    <input id="name" class="@error('name') is-invalid @enderror" type="text" name="name" value="{{ old('name', $profileUser->name) }}" required @error('name') aria-invalid="true" aria-describedby="profileNameError" @enderror>
                    @error('name')<p class="profile-field-error" id="profileNameError">{{ $message }}</p>@enderror

                    <label for="email">Email</label>
                    <input id="email" class="@error('email') is-invalid @enderror" type="email" name="email" value="{{ old('email', $profileUser->email) }}" required @error('email') aria-invalid="true" aria-describedby="profileEmailError" @enderror>
                    @error('email')<p class="profile-field-error" id="profileEmailError">{{ $message }}</p>@enderror

                    <dl class="profile-account-status">
                        <div><dt>Account Status</dt><dd class="{{ $profileUser->isActive() ? 'status-active' : 'status-pending' }}">{{ Str::headline($profileUser->status) }}</dd></div>
                        <div><dt>Role</dt><dd>{{ $displayRole }}</dd></div>
                        <div><dt>Email Status</dt><dd class="{{ $profileUser->email_verified_at ? 'status-active' : 'status-pending' }}">{{ $profileUser->email_verified_at ? 'Verified' : 'Not verified' }}</dd></div>
                    </dl>

                    <button class="profile-panel__submit" type="submit">Save Profile</button>
                </form>

                <form class="profile-panel" action="{{ route('profile.password.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <h2>Change Password</h2>

                    <label for="current_password">Current password</label>
                    <div class="profile-password-field">
                        <input id="current_password" class="@error('current_password') is-invalid @enderror" type="password" name="current_password" required autocomplete="current-password" @error('current_password') aria-invalid="true" aria-describedby="currentPasswordError" @enderror>
                        <button class="profile-password-toggle" type="button" data-profile-password-toggle="current_password" aria-label="Show current password" aria-controls="current_password" aria-pressed="false"><i class="fa-solid fa-eye" aria-hidden="true"></i></button>
                    </div>
                    @error('current_password')<p class="profile-field-error" id="currentPasswordError">{{ $message }}</p>@enderror

                    <label for="password">New password</label>
                    <div class="profile-password-field">
                        <input id="password" class="@error('password') is-invalid @enderror" type="password" name="password" required autocomplete="new-password" aria-describedby="profilePasswordRequirements @error('password') newPasswordError @enderror">
                        <button class="profile-password-toggle" type="button" data-profile-password-toggle="password" aria-label="Show new password" aria-controls="password" aria-pressed="false"><i class="fa-solid fa-eye" aria-hidden="true"></i></button>
                    </div>
                    <small class="profile-password-helper" id="profilePasswordRequirements">At least 8 characters with uppercase, lowercase, and a number.</small>
                    @error('password')<p class="profile-field-error" id="newPasswordError">{{ $message }}</p>@enderror

                    <label for="password_confirmation">Confirm new password</label>
                    <div class="profile-password-field">
                        <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password">
                        <button class="profile-password-toggle" type="button" data-profile-password-toggle="password_confirmation" aria-label="Show password confirmation" aria-controls="password_confirmation" aria-pressed="false"><i class="fa-solid fa-eye" aria-hidden="true"></i></button>
                    </div>

                    <button class="profile-panel__submit" type="submit">Update Password</button>
                </form>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        (() => {
            const input = document.getElementById('avatar');
            const removeInput = document.getElementById('removeAvatarInput');
            const chooseButton = document.getElementById('chooseAvatar');
            const editButton = document.getElementById('avatarEditButton');
            const removeButton = document.getElementById('removeAvatar');
            const image = document.getElementById('profilePhotoImage');
            const initials = document.getElementById('profilePhotoInitials');
            const fileName = document.getElementById('avatarFileName');
            const error = document.getElementById('avatarError');
            const allowedExtensions = ['png', 'jpg', 'jpeg', 'webp'];
            const maximumSize = 2 * 1024 * 1024;
            let previewUrl = null;

            function showError(message) { error.textContent = message; }

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
                initials.hidden = true;
                fileName.textContent = file.name;
                error.textContent = '';
                removeButton.hidden = false;
                removeInput.value = '0';
            });

            chooseButton.addEventListener('click', () => input.click());
            editButton.addEventListener('click', () => input.click());
            removeButton.addEventListener('click', () => {
                input.value = '';
                removeInput.value = '1';
                image.hidden = true;
                image.removeAttribute('src');
                initials.hidden = false;
                fileName.textContent = 'Photo will be removed after saving.';
                error.textContent = '';
                removeButton.hidden = true;
                if (previewUrl) URL.revokeObjectURL(previewUrl);
                previewUrl = null;
            });

            document.querySelectorAll('[data-profile-password-toggle]').forEach((toggle) => {
                const passwordInput = document.getElementById(toggle.dataset.profilePasswordToggle);
                const icon = toggle.querySelector('i');
                if (!passwordInput || !icon) return;
                toggle.addEventListener('click', () => {
                    const showing = passwordInput.type === 'text';
                    passwordInput.type = showing ? 'password' : 'text';
                    icon.classList.toggle('fa-eye', showing);
                    icon.classList.toggle('fa-eye-slash', !showing);
                    toggle.setAttribute('aria-label', `${showing ? 'Show' : 'Hide'} ${passwordInput.labels?.[0]?.textContent?.toLowerCase() || 'password'}`);
                    toggle.setAttribute('aria-pressed', showing ? 'false' : 'true');
                    passwordInput.focus();
                });
            });
        })();
    </script>
@endpush
