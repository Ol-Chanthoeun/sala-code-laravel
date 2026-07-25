@extends('layouts.frontend')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    .contact-page {
        font-family: 'Inter', sans-serif;
        position: relative;
        isolation: isolate;
        overflow: hidden;
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
        overflow: hidden;
        box-shadow: 0 20px 60px rgba(0,0,0,0.18);
    }

    .contact-info {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
        align-items: center;
        margin-top: 25px;
    }

    .info-icon {
        width: 50px;
        height: 50px;
        border-radius: 14px;
        background: rgba(255,255,255,0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
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
    }

    .contact-form-side {
        padding: 45px 35px;
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
        margin-bottom: 20px;
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
        width: 100%;
        padding: 14px 16px;
        border: 2px solid #e2e8f0;
        border-radius: 14px;
        font-size: 16px;
        font-family: 'Inter', sans-serif;
    }

    .form-group textarea {
        min-height: 130px;
        resize: vertical;
    }

    .btn-submit {
        width: 100%;
        padding: 15px;
        border: none;
        border-radius: 14px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: #fff;
        font-size: 17px;
        font-weight: 700;
        cursor: pointer;
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
        width: 100%;
        height: 100%;
        border: none;
    }

    @media (max-width: 768px) {
        .contact-grid {
            grid-template-columns: 1fr;
        }

        .contact-header h1 {
            font-size: 32px;
        }
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
                        <p>{{ $systemSettings['contact_phone'] ?: '+855 12 345 678' }}</p>
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div>
                        <h4>Email Us</h4>
                        <p>{{ $systemSettings['contact_email'] ?: 'info@sala-code.com' }}</p>
                    </div>
                </div>

                <div class="social-links">
                    <a href="{{ $systemSettings['facebook_url'] ?: '#' }}"><i class="fab fa-facebook-f"></i></a>
                    <a href="{{ $systemSettings['telegram_url'] ?: '#' }}"><i class="fab fa-telegram"></i></a>
                    <a href="{{ $systemSettings['youtube_url'] ?: '#' }}"><i class="fab fa-youtube"></i></a>
                    <a href="{{ $systemSettings['github_url'] ?: '#' }}"><i class="fab fa-github"></i></a>
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

                <form action="{{ route('contact.store') }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label>
                            <i class="fas fa-user"></i> Full Name
                        </label>
                        <input type="text" name="name" value="{{ old('name') }}" required>
                    </div>

                    <div class="form-group">
                        <label>
                            <i class="fas fa-envelope"></i> Email Address
                        </label>
                        <input type="email" name="email" value="{{ old('email') }}" required>
                    </div>

                    <div class="form-group">
                        <label>
                            <i class="fas fa-comment"></i> Message
                        </label>
                        <textarea name="message" required>{{ old('message') }}</textarea>
                    </div>

                    <button type="submit" class="btn-submit">
                        <i class="fas fa-paper-plane"></i>
                        Send Message
                    </button>
                </form>
            </div>

        </div>

        <div class="map-section">
            <div class="map-container">
                <iframe 
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3909.014719965848!2d104.892396!3d11.556374!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x310951add5e2cd81%3A0x171e0b69c7c6f7ba!2sPhnom%20Penh!5e0!3m2!1sen!2skh!4v1699999999999!5m2!1sen!2skh" 
                    allowfullscreen="" 
                    loading="lazy">
                </iframe>
            </div>
        </div>

    </div>
</section>

@endsection
