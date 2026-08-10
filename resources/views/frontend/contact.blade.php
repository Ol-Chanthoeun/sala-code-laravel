@extends('layouts.frontend')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    .contact-page {
        font-family: 'Inter', sans-serif;
        position: relative;
        isolation: isolate;
        overflow: clip;
        background:
            radial-gradient(circle at 12% 18%, rgba(56, 189, 248, 0.24) 0, rgba(56, 189, 248, 0) 28%),
            radial-gradient(circle at 86% 12%, rgba(124, 58, 237, 0.26) 0, rgba(124, 58, 237, 0) 30%),
            radial-gradient(circle at 78% 82%, rgba(14, 165, 233, 0.18) 0, rgba(14, 165, 233, 0) 26%),
            linear-gradient(135deg, #eef7ff 0%, #e8edff 34%, #f4efff 68%, #eaf9ff 100%);
        min-height: 100vh;
        padding: 70px 30px;
    }

    .contact-page::before {
        content: "";
        position: absolute;
        inset: 0;
        z-index: -2;
        pointer-events: none;
        background-image:
            linear-gradient(rgba(37, 99, 235, 0.07) 1px, transparent 1px),
            linear-gradient(90deg, rgba(37, 99, 235, 0.07) 1px, transparent 1px),
            linear-gradient(135deg, rgba(99, 102, 241, 0.10) 0 1px, transparent 1px 78px),
            radial-gradient(circle at 32% 30%, rgba(255, 255, 255, 0.72) 0 2px, transparent 3px),
            radial-gradient(circle at 66% 64%, rgba(37, 99, 235, 0.18) 0 2px, transparent 3px);
        background-size: 72px 72px, 72px 72px, 156px 156px, 180px 180px, 220px 220px;
        mask-image: linear-gradient(to bottom, rgba(0,0,0,0.72), rgba(0,0,0,0.22));
    }

    .contact-page::after {
        content: "</>   { }   01   fn()   #";
        position: absolute;
        inset: 110px auto auto 5%;
        z-index: -1;
        width: 90%;
        color: rgba(30, 64, 175, 0.10);
        font: 800 56px/1.8 Consolas, Monaco, monospace;
        letter-spacing: 22px;
        white-space: normal;
        pointer-events: none;
        text-shadow:
            0 0 44px rgba(56, 189, 248, 0.22),
            0 0 72px rgba(124, 58, 237, 0.18);
        transform: rotate(-4deg);
    }

    .contact-container {
        max-width: 1200px;
        margin: auto;
        position: relative;
        z-index: 1;
    }

    .contact-header {
        text-align: center;
        margin-bottom: 40px;
    }

    .contact-header h1 {
        font-size: 42px;
        color: #111827;
        font-weight: 800;
        margin-bottom: 12px;
        text-shadow: 0 2px 12px rgba(255,255,255,0.45);
    }

    .contact-header p {
        color: #475569;
        font-size: 18px;
        font-weight: 500;
        text-shadow: 0 1px 10px rgba(255,255,255,0.35);
    }

    .contact-grid {
        display: grid;
        grid-template-columns: 1fr 1.5fr;
        gap: 0;
        background: #fff;
        border-radius: 22px;
        overflow: visible;
        box-shadow: 0 20px 60px rgba(0,0,0,0.18);
    }

    .contact-info {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 22px 0 0 22px;
        min-width: 0;
        padding: 45px 35px;
        color: #fff;
    }

    .contact-info h2 {
        font-size: 30px;
        margin-bottom: 15px;
    }

    .contact-info p {
        line-height: 1.7;
    }

    .info-item {
        display: flex;
        gap: 15px;
        align-items: flex-start;
        margin-top: 26px;
        min-width: 0;
    }

    .info-icon {
        width: 50px;
        height: 50px;
        border-radius: 14px;
        background: rgba(255,255,255,0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 50px;
        font-size: 22px;
    }

    .info-item > div:last-child {
        min-width: 0;
        padding-top: 2px;
    }

    .info-item h4 {
        font-size: 16px;
        line-height: 1.35;
        margin: 0 0 4px;
    }

    .info-item p {
        margin: 0;
        overflow-wrap: anywhere;
        word-break: break-word;
    }

    .info-item a {
        color: #fff;
        text-decoration: none;
    }

    .info-item a:hover,
    .info-item a:focus-visible {
        text-decoration: underline;
    }

    .social-links {
        display: flex;
        gap: 12px;
        margin-top: 35px;
    }

    .social-links a {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        background: rgba(255,255,255,0.2);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        transition: background-color .2s ease, transform .2s ease;
    }

    .social-links a:hover,
    .social-links a:focus-visible {
        background: rgba(255,255,255,0.32);
        transform: translateY(-2px);
    }

    .contact-form-side {
        border-radius: 0 22px 22px 0;
        min-width: 0;
        padding: 45px 35px 32px;
    }

    .form-title {
        font-size: 30px;
        margin-bottom: 8px;
    }

    .form-subtitle {
        color: #666;
        margin-bottom: 28px;
    }

    .form-group {
        margin-bottom: 18px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
    }

    .form-group label i {
        color: #667eea;
        margin-right: 8px;
    }

    .form-group input,
    .form-group textarea {
        background: #fff;
        width: 100%;
        padding: 14px 16px;
        border: 2px solid #e2e8f0;
        border-radius: 14px;
        font-size: 16px;
        font-family: 'Inter', sans-serif;
        line-height: 1.5;
        outline: none;
        transition: border-color .2s ease, box-shadow .2s ease;
    }

    .form-group input:focus,
    .form-group textarea:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102,126,234,.14);
    }

    .form-group textarea {
        min-height: 130px;
        resize: vertical;
    }

    .field-error {
        color: #b91c1c;
        display: block;
        font-size: 14px;
        margin-top: 6px;
    }

    .btn-submit {
        align-items: center;
        display: inline-flex;
        gap: 9px;
        justify-content: center;
        width: 100%;
        min-height: 52px;
        padding: 13px 18px;
        border: none;
        border-radius: 14px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: #fff;
        font-size: 17px;
        font-weight: 700;
        cursor: pointer;
        margin: 18px 0 8px;
        transition: box-shadow .2s ease, transform .2s ease;
    }

    .btn-submit:hover,
    .btn-submit:focus-visible {
        box-shadow: 0 8px 20px rgba(102,126,234,.3);
        transform: translateY(-1px);
    }

    .contact-form {
        overflow: visible;
        padding-bottom: 24px;
    }

    .login-required {
        align-items: flex-start;
        background: #f5f3ff;
        border: 1px solid #ddd6fe;
        border-radius: 12px;
        color: #4c1d95;
        display: flex;
        gap: 10px;
        line-height: 1.5;
        margin-top: 18px;
        padding: 14px;
    }

    .login-required i {
        margin-top: 4px;
    }

    .login-required a {
        color: #4f46e5;
        font-weight: 700;
    }

    .alert-success {
        background: #d4edda;
        color: #155724;
        padding: 15px;
        border-radius: 12px;
        margin-bottom: 20px;
    }

    .alert-danger {
        background: #f8d7da;
        color: #721c24;
        padding: 15px;
        border-radius: 12px;
        margin-bottom: 20px;
    }

    .map-section {
        margin-top: 40px;
        background: #fff;
        border-radius: 22px;
        overflow: hidden;
        box-shadow: 0 20px 60px rgba(0,0,0,0.18);
    }

    .map-container {
        height: 350px;
    }

    .map-container iframe {
        display: block;
        width: 100%;
        height: 100%;
        border: none;
    }

    @media (max-width: 768px) {
        .contact-grid {
            grid-template-columns: 1fr;
        }

        .contact-page {
            padding: 48px 18px;
        }

        .contact-info {
            border-radius: 22px 22px 0 0;
        }

        .contact-form-side {
            border-radius: 0 0 22px 22px;
            padding: 34px 24px 28px;
        }

        .map-container {
            height: 280px;
        }

        .contact-header h1 {
            font-size: 32px;
        }
    }

    @media (max-width: 480px) {
        .contact-page { padding-left: 12px; padding-right: 12px; }
        .contact-info { padding: 34px 22px; }
        .contact-form-side { padding-left: 20px; padding-right: 20px; }
        .social-links { flex-wrap: wrap; }
        .map-container { height: 260px; }
    }
</style>
@endpush

@section('content')

<section class="contact-page">
    <div class="contact-container">

        <div class="contact-header">
            <h1>Contact Us</h1>
            <p>We'd love to hear from you! Send us a message.</p>
        </div>

        <div class="contact-grid">

            <div class="contact-info">
                <h2>Get in Touch</h2>
                <p>
                    Have questions about our courses? Need technical support?
                    We're here to help you.
                </p>

                <div class="info-item">
                    <div class="info-icon">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <div>
                        <h4>Visit Us</h4>
                        <p>{{ $systemSettings['contact_address'] ?: 'Phnom Penh, Cambodia' }}</p>
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-icon">
                        <i class="fas fa-phone-alt"></i>
                    </div>
                    <div>
                        <h4>Call Us</h4>
                        <p><a href="tel:+855962796742">+855 96 279 6742</a></p>
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div>
                        <h4>Email Us</h4>
                        <p><a href="mailto:olchanthoeun007@gmail.com">olchanthoeun007@gmail.com</a></p>
                    </div>
                </div>

                <div class="social-links">
                    <a href="{{ $systemSettings['facebook_url'] ?: '#' }}" aria-label="Facebook"><i class="fab fa-facebook-f" aria-hidden="true"></i></a>
                    <a href="{{ $systemSettings['telegram_url'] ?: '#' }}" aria-label="Telegram"><i class="fab fa-telegram" aria-hidden="true"></i></a>
                    <a href="{{ $systemSettings['youtube_url'] ?: '#' }}" aria-label="YouTube"><i class="fab fa-youtube" aria-hidden="true"></i></a>
                    <a href="{{ $systemSettings['github_url'] ?: '#' }}" aria-label="GitHub"><i class="fab fa-github" aria-hidden="true"></i></a>
                </div>
            </div>

            <div class="contact-form-side">
                <h2 class="form-title">Send a Message</h2>
                <p class="form-subtitle">Fill out the form below and we'll get back to you.</p>

                @if(session('success'))
                    <div class="alert-success">
                        <i class="fas fa-check-circle"></i>
                        {{ session('success') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert-danger">
                        @foreach($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <form class="contact-form" action="{{ route('contact.store') }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label for="contact-name">
                            <i class="fas fa-user"></i> Full Name
                        </label>
                        <input id="contact-name" type="text" name="name" value="{{ old('name', auth()->user()?->name) }}" minlength="2" maxlength="100" autocomplete="name" required @auth readonly @endauth>
                        @error('name')<span class="field-error">{{ $message }}</span>@enderror
                    </div>

                    <div class="form-group">
                        <label for="contact-email">
                            <i class="fas fa-envelope"></i> Email Address
                        </label>
                        <input id="contact-email" type="email" name="email" value="{{ old('email', auth()->user()?->email) }}" maxlength="255" autocomplete="email" required @auth readonly @endauth>
                        @error('email')<span class="field-error">{{ $message }}</span>@enderror
                    </div>

                    <div class="form-group">
                        <label for="contact-message">
                            <i class="fas fa-comment"></i> Message
                        </label>
                        <textarea id="contact-message" name="message" minlength="10" maxlength="1000" required>{{ old('message') }}</textarea>
                        @error('message')<span class="field-error">{{ $message }}</span>@enderror
                    </div>

                    @auth
                        <button type="submit" class="btn-submit">
                            <i class="fas fa-paper-plane"></i>
                            Send Message
                        </button>
                    @else
                        <div class="login-required" role="status"><i class="fas fa-lock" aria-hidden="true"></i><div>Please log in before sending a message. <a href="{{ route('login') }}">Login</a></div></div>
                    @endauth
                </form>
            </div>

        </div>

        <div class="map-section">
            <div class="map-container">
                <iframe 
                    src="https://www.google.com/maps?q=Passerelles%20Num%C3%A9riques%20Cambodia%2C%20Street%20371%2C%20Phnom%20Penh&z=15&output=embed"
                    title="Passerelles Numériques Cambodia location"
                    allowfullscreen="" 
                    loading="lazy">
                </iframe>
            </div>
        </div>

    </div>
</section>

@endsection
