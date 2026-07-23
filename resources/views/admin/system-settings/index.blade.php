@extends('layouts.admin')

@section('title', 'System Settings')
@section('page-title', 'System Settings')
@section('breadcrumb', 'System Settings')

@push('styles')
<style>
    .settings-form { display:grid; gap:20px; }
    .settings-section { background:#fff; border:1px solid #e2e8f0; border-radius:10px; padding:22px; }
    .settings-section h3 { color:#0f172a; margin-bottom:16px; }
    .settings-grid { display:grid; gap:16px; grid-template-columns:repeat(2,minmax(0,1fr)); }
    .settings-field.full { grid-column:1/-1; }
    .settings-field label { color:#374151; display:block; font-weight:700; margin-bottom:7px; }
    .settings-field input:not([type=checkbox]):not([type=color]), .settings-field select, .settings-field textarea { border:1px solid #cbd5e1; border-radius:7px; padding:11px 12px; width:100%; }
    .settings-field textarea { min-height:100px; resize:vertical; }
    .settings-toggle { align-items:center; display:flex; gap:10px; padding:9px 0; }
    .settings-toggle input { height:18px; width:18px; }
    .settings-color { align-items:center; display:flex; gap:10px; }
    .settings-color input[type=color] { border:1px solid #cbd5e1; border-radius:7px; height:44px; padding:3px; width:62px; }
    .settings-preview { border:1px solid #e2e8f0; border-radius:8px; display:block; margin-top:10px; max-height:110px; max-width:220px; object-fit:contain; padding:6px; }
    .settings-actions { display:flex; flex-wrap:wrap; gap:10px; }
    .settings-button { background:#4f46e5; border:0; border-radius:7px; color:#fff; cursor:pointer; font-weight:700; padding:11px 18px; }
    .settings-button.danger { background:#dc2626; }
    @media(max-width:700px){ .settings-grid{grid-template-columns:1fr}.settings-field.full{grid-column:auto} }
</style>
@endpush

@section('content')
    @if(session('success'))<p style="color:green;margin-bottom:15px;">{{ session('success') }}</p>@endif
    @if($errors->any())<p style="color:#dc2626;margin-bottom:15px;">{{ $errors->first() }}</p>@endif

    <form class="settings-form" action="{{ route('admin.system-settings.update') }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')

        <section class="settings-section">
            <h3><i class="fas fa-globe"></i> General</h3>
            <div class="settings-grid">
                <div class="settings-field"><label for="website_name">Website Name</label><input id="website_name" name="website_name" value="{{ old('website_name', $settings['website_name']) }}" required></div>
                <div class="settings-field"><label for="default_language">Default Language</label><select id="default_language" name="default_language"><option value="en" @selected(old('default_language', $settings['default_language']) === 'en')>English</option><option value="km" @selected(old('default_language', $settings['default_language']) === 'km')>Khmer</option></select></div>
                <div class="settings-field"><label for="website_logo">Website Logo</label><input id="website_logo" type="file" name="website_logo" accept=".png,.jpg,.jpeg,.svg,.webp" data-preview="websiteLogoPreview">@if($settings['website_logo'])<img id="websiteLogoPreview" class="settings-preview" src="{{ Storage::url($settings['website_logo']) }}" alt="Website logo">@else<img id="websiteLogoPreview" class="settings-preview" alt="Website logo preview" hidden>@endif</div>
                <div class="settings-field"><label for="favicon">Favicon</label><input id="favicon" type="file" name="favicon" accept=".png,.jpg,.jpeg,.svg,.webp,.ico" data-preview="faviconPreview">@if($settings['favicon'])<img id="faviconPreview" class="settings-preview" src="{{ Storage::url($settings['favicon']) }}" alt="Favicon">@else<img id="faviconPreview" class="settings-preview" alt="Favicon preview" hidden>@endif</div>
                <div class="settings-field full"><label for="website_description">Website Description</label><textarea id="website_description" name="website_description">{{ old('website_description', $settings['website_description']) }}</textarea></div>
                <div class="settings-field full"><label for="time_zone">Time Zone</label><select id="time_zone" name="time_zone">@foreach(DateTimeZone::listIdentifiers() as $zone)<option value="{{ $zone }}" @selected(old('time_zone', $settings['time_zone']) === $zone)>{{ $zone }}</option>@endforeach</select></div>
            </div>
        </section>

        <section class="settings-section">
            <h3><i class="fas fa-house"></i> Homepage</h3>
            <div class="settings-grid">
                <div class="settings-field"><label for="hero_title">Hero Title</label><input id="hero_title" name="hero_title" value="{{ old('hero_title', $settings['hero_title']) }}"></div>
                <div class="settings-field"><label for="hero_image">Hero Image</label><input id="hero_image" type="file" name="hero_image" accept=".png,.jpg,.jpeg,.webp" data-preview="heroImagePreview">@if($settings['hero_image'])<img id="heroImagePreview" class="settings-preview" src="{{ Storage::url($settings['hero_image']) }}" alt="Hero image">@else<img id="heroImagePreview" class="settings-preview" alt="Hero image preview" hidden>@endif</div>
                <div class="settings-field full"><label for="hero_subtitle">Hero Subtitle</label><textarea id="hero_subtitle" name="hero_subtitle">{{ old('hero_subtitle', $settings['hero_subtitle']) }}</textarea></div>
            </div>
        </section>

        <section class="settings-section">
            <h3><i class="fas fa-address-book"></i> Contact</h3>
            <div class="settings-grid">
                <div class="settings-field"><label for="contact_email">Email</label><input id="contact_email" type="email" name="contact_email" value="{{ old('contact_email', $settings['contact_email']) }}"></div>
                <div class="settings-field"><label for="contact_phone">Phone</label><input id="contact_phone" name="contact_phone" value="{{ old('contact_phone', $settings['contact_phone']) }}"></div>
                <div class="settings-field full"><label for="contact_address">Address</label><textarea id="contact_address" name="contact_address">{{ old('contact_address', $settings['contact_address']) }}</textarea></div>
                @foreach(['facebook_url'=>'Facebook','telegram_url'=>'Telegram','youtube_url'=>'YouTube','github_url'=>'GitHub'] as $key=>$label)<div class="settings-field"><label for="{{ $key }}">{{ $label }}</label><input id="{{ $key }}" type="url" name="{{ $key }}" value="{{ old($key, $settings[$key]) }}" placeholder="https://"></div>@endforeach
            </div>
        </section>

        <section class="settings-section">
            <h3><i class="fas fa-lock"></i> Authentication</h3>
            @foreach(['enable_google_login'=>'Enable Google Login','enable_registration'=>'Enable Registration','enable_forgot_password'=>'Enable Forgot Password'] as $key=>$label)
                <label class="settings-toggle"><input type="hidden" name="{{ $key }}" value="0"><input type="checkbox" name="{{ $key }}" value="1" @checked(old($key, $settings[$key]))> {{ $label }}</label>
            @endforeach
        </section>

        <section class="settings-section">
            <h3><i class="fas fa-palette"></i> Appearance</h3>
            <div class="settings-grid">
                <div class="settings-field"><label for="primary_color">Primary Color</label><div class="settings-color"><input id="primary_color" type="color" name="primary_color" value="{{ old('primary_color', $settings['primary_color']) }}"><span>{{ old('primary_color', $settings['primary_color']) }}</span></div></div>
                <div class="settings-field"><label for="secondary_color">Secondary Color</label><div class="settings-color"><input id="secondary_color" type="color" name="secondary_color" value="{{ old('secondary_color', $settings['secondary_color']) }}"><span>{{ old('secondary_color', $settings['secondary_color']) }}</span></div></div>
                <div class="settings-field full"><label for="footer_text">Footer Text</label><input id="footer_text" name="footer_text" value="{{ old('footer_text', $settings['footer_text']) }}"></div>
            </div>
        </section>

        <section class="settings-section">
            <h3><i class="fas fa-user-shield"></i> Admin</h3>
            <label class="settings-toggle"><input type="hidden" name="maintenance_mode" value="0"><input type="checkbox" name="maintenance_mode" value="1" @checked(old('maintenance_mode', $settings['maintenance_mode']))> Maintenance Mode</label>
            <label class="settings-toggle"><input type="hidden" name="debug_mode" value="0"><input type="checkbox" name="debug_mode" value="1" @checked(old('debug_mode', $settings['debug_mode']))> Debug Mode</label>
        </section>

        <div class="settings-actions"><button class="settings-button" type="submit"><i class="fas fa-save"></i> Save Settings</button></div>
    </form>

    <form action="{{ route('admin.system-settings.reset') }}" method="POST" style="margin-top:10px;">@csrf @method('DELETE')<button class="settings-button danger" type="submit" onclick="return confirm('Reset all system settings to their defaults?')"><i class="fas fa-rotate-left"></i> Reset Settings</button></form>
@endsection

@push('scripts')
<script>
    document.querySelectorAll('input[type="file"][data-preview]').forEach((input) => {
        input.addEventListener('change', () => {
            const file = input.files[0];
            const preview = document.getElementById(input.dataset.preview);
            if (!file || !preview) return;
            preview.src = URL.createObjectURL(file);
            preview.hidden = false;
        });
    });
    document.querySelectorAll('input[type="color"]').forEach((input) => input.addEventListener('input', () => input.nextElementSibling.textContent = input.value));
</script>
@endpush
