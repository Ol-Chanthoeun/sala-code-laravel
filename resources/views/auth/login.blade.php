@extends('auth.layout')

@section('title', 'Login')
@section('body-class', 'login-page')

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body.login-page {
            background:
                radial-gradient(circle at 15% 18%, rgba(59, 130, 246, .08) 0, rgba(59, 130, 246, 0) 28%),
                radial-gradient(circle at 85% 82%, rgba(79, 70, 229, .07) 0, rgba(79, 70, 229, 0) 26%),
                linear-gradient(145deg, #f7faff 0%, #edf4ff 100%);
            overflow-x: hidden;
            position: relative;
        }

        .login-page .auth-box {
            border-color: #dfe7f2;
            border-radius: 12px;
            box-shadow: 0 18px 45px rgba(30, 64, 175, .11);
            max-width: 470px;
            padding: 34px 36px;
            position: relative;
        }

        .login-page .brand {
            gap: 11px;
            margin-bottom: 22px;
            text-decoration: none;
        }

        .login-page .brand img {
            display: block;
            object-fit: cover;
        }

        .login-page form > label:not(.checkbox-row) {
            font-size: 14px;
            margin-top: 16px;
        }

        .login-page input[type="email"],
        .login-page input[type="password"],
        .login-page input[type="text"] {
            background: #fff;
            border: 1px solid #d6dce8;
            border-radius: 8px;
            font-size: 15px;
            height: 48px;
            outline: none;
            padding: 0 14px;
            transition: border-color .18s ease, box-shadow .18s ease;
        }

        .login-page input[type="email"]:hover,
        .login-page input[type="password"]:hover,
        .login-page input[type="text"]:hover {
            border-color: #aeb9ca;
        }

        .login-page input[type="email"]:focus,
        .login-page input[type="password"]:focus,
        .login-page input[type="text"]:focus {
            border-color: #2876e8;
            box-shadow: 0 0 0 3px rgba(40, 118, 232, .13);
            outline: none;
        }

        .login-page input.is-invalid {
            border-color: #dc2626;
        }

        .login-page input.is-invalid:focus {
            border-color: #dc2626;
            box-shadow: 0 0 0 3px rgba(220, 38, 38, .1);
        }

        .password-field {
            position: relative;
        }

        .login-page .password-field input {
            padding-right: 48px;
        }

        .password-toggle {
            align-items: center;
            background: transparent;
            border: 0;
            border-radius: 6px;
            color: #64748b;
            cursor: pointer;
            display: inline-flex;
            height: 36px;
            justify-content: center;
            padding: 0;
            position: absolute;
            right: 6px;
            top: 6px;
            width: 36px;
        }

        .password-toggle:hover { color: #2876e8; }
        .password-toggle:focus-visible { box-shadow: 0 0 0 3px rgba(40, 118, 232, .14); outline: none; }

        .login-page .checkbox-row {
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            margin: 14px 0 16px;
            min-height: 28px;
            width: fit-content;
        }

        .login-page .checkbox-row input {
            accent-color: #2876e8;
            height: 16px;
            width: 16px;
        }

        .login-page .btn {
            border-radius: 8px;
            height: 48px;
            padding: 0 16px;
            transition: background-color .18s ease, border-color .18s ease, box-shadow .18s ease, transform .18s ease;
        }

        .login-page .btn-primary:hover { background: #195fc5; }
        .login-page .btn-primary:focus-visible { box-shadow: 0 0 0 3px rgba(40, 118, 232, .18); outline: none; }

        .login-page .btn-google {
            border-color: #d6dce8;
            color: #334155;
            font-weight: 600;
            position: relative;
        }

        .login-page .btn-google i {
            color: #4285f4;
            font-size: 18px;
            left: 17px;
            position: absolute;
        }

        .login-page .btn-google:hover {
            background: #f8fafc;
            border-color: #aeb9ca;
        }

        .login-page .btn-google:focus-visible { box-shadow: 0 0 0 3px rgba(40, 118, 232, .12); outline: none; }

        .login-page .links {
            align-items: center;
            font-size: 14px;
            margin-top: 20px;
        }

        .login-page .links a {
            color: #4777b9;
            text-decoration: none;
        }

        .login-page .links a:hover {
            color: #1f5eb8;
            text-decoration: underline;
            text-underline-offset: 3px;
        }

        @media (max-width: 520px) {
            body.login-page { padding: 16px; }
            .login-page .auth-box {
                max-width: none;
                padding: 28px 22px;
                width: 100%;
            }
        }
    </style>
@endpush

@section('content')
    <h1>Login</h1>
    <p class="muted">Access your Sala Code profile and continue learning.</p>

    <form action="{{ route('login.post') }}" method="POST">
        @csrf

        <label for="email">Email</label>
        <input id="email" class="@error('email') is-invalid @enderror" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="email" @error('email') aria-invalid="true" aria-describedby="emailError" @enderror>
        @error('email')
            <p class="field-error" id="emailError">{{ $message }}</p>
        @enderror

        <label for="password">Password</label>
        <div class="password-field">
            <input id="password" class="@error('password') is-invalid @enderror" type="password" name="password" required autocomplete="current-password" @error('password') aria-invalid="true" aria-describedby="passwordError" @enderror>
            <button class="password-toggle" id="passwordToggle" type="button" aria-label="Show password" aria-controls="password" aria-pressed="false">
                <i class="fa-solid fa-eye" aria-hidden="true"></i>
            </button>
        </div>
        @error('password')
            <p class="field-error" id="passwordError">{{ $message }}</p>
        @enderror

        <label class="checkbox-row">
            <input type="checkbox" name="remember" value="1" @checked(old('remember'))>
            <span>Remember me</span>
        </label>

        <button class="btn btn-primary" type="submit">Login</button>
    </form>

    @if(($systemSettings['enable_google_login'] ?? true))
        <a class="btn btn-google" href="{{ route('google.redirect') }}"><i class="fa-brands fa-google" aria-hidden="true"></i><span>Continue with Google</span></a>
    @endif

    <div class="links">
        @if(($systemSettings['enable_forgot_password'] ?? true))<a href="{{ route('password.request') }}">Forgot password?</a>@endif
        @if(($systemSettings['enable_registration'] ?? true))<a href="{{ route('register') }}">Create account</a>@endif
    </div>
@endsection

@push('scripts')
    <script>
        (() => {
            const password = document.getElementById('password');
            const toggle = document.getElementById('passwordToggle');
            const icon = toggle?.querySelector('i');

            if (!password || !toggle || !icon) return;

            toggle.addEventListener('click', () => {
                const showing = password.type === 'text';
                password.type = showing ? 'password' : 'text';
                icon.classList.toggle('fa-eye', showing);
                icon.classList.toggle('fa-eye-slash', !showing);
                toggle.setAttribute('aria-label', showing ? 'Show password' : 'Hide password');
                toggle.setAttribute('aria-pressed', showing ? 'false' : 'true');
                password.focus();
            });
        })();
    </script>
@endpush
