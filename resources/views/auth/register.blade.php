@extends('auth.layout')

@section('title', 'Register')
@section('body-class', 'register-page')

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body.register-page {
            background:
                radial-gradient(circle at 15% 18%, rgba(59, 130, 246, .08) 0, rgba(59, 130, 246, 0) 28%),
                radial-gradient(circle at 85% 82%, rgba(79, 70, 229, .07) 0, rgba(79, 70, 229, 0) 26%),
                linear-gradient(145deg, #f7faff 0%, #edf4ff 100%);
            overflow-x: hidden;
            position: relative;
        }

        .register-page .auth-box {
            border-color: #dfe7f2;
            border-radius: 12px;
            box-shadow: 0 18px 45px rgba(30, 64, 175, .11);
            max-width: 470px;
            padding: 28px 36px;
            position: relative;
        }

        .register-page .brand {
            gap: 11px;
            margin-bottom: 16px;
            text-decoration: none;
        }

        .register-page .brand img { display: block; object-fit: cover; }

        .register-page .muted {
            font-size: 14px;
            line-height: 1.55;
            margin: 0 auto 14px;
            max-width: 370px;
        }

        .register-page form > label {
            font-size: 14px;
            margin-top: 12px;
        }

        .register-page input[type="text"],
        .register-page input[type="email"],
        .register-page input[type="password"] {
            background: #fff;
            border: 1px solid #d6dce8;
            border-radius: 8px;
            font-size: 15px;
            height: 48px;
            outline: none;
            padding: 0 14px;
            transition: border-color .18s ease, box-shadow .18s ease;
        }

        .register-page input[type="text"]:hover,
        .register-page input[type="email"]:hover,
        .register-page input[type="password"]:hover { border-color: #aeb9ca; }

        .register-page input[type="text"]:focus,
        .register-page input[type="email"]:focus,
        .register-page input[type="password"]:focus {
            border-color: #2876e8;
            box-shadow: 0 0 0 3px rgba(40, 118, 232, .13);
            outline: none;
        }

        .register-page input.is-invalid { border-color: #dc2626; }
        .register-page input.is-invalid:focus {
            border-color: #dc2626;
            box-shadow: 0 0 0 3px rgba(220, 38, 38, .1);
        }

        .password-field { position: relative; }
        .register-page .password-field input { padding-right: 48px; }

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

        .password-helper {
            color: #64748b;
            display: block;
            font-size: 12px;
            line-height: 1.4;
            margin-top: 4px;
        }

        .password-match-error {
            color: #b91c1c;
            font-size: 13px;
            margin-top: 6px;
        }

        .register-page .btn {
            border-radius: 8px;
            height: 48px;
            padding: 0 16px;
            transition: background-color .18s ease, border-color .18s ease, box-shadow .18s ease;
        }

        .register-page .btn-primary { margin-top: 14px; }
        .register-page .btn-primary:hover { background: #195fc5; }
        .register-page .btn-primary:focus-visible { box-shadow: 0 0 0 3px rgba(40, 118, 232, .18); outline: none; }

        .register-page .btn-google {
            border-color: #d6dce8;
            color: #334155;
            font-weight: 600;
            position: relative;
            margin-top: 10px;
        }

        .register-page .btn-google i {
            color: #4285f4;
            font-size: 18px;
            left: 17px;
            position: absolute;
        }

        .register-page .btn-google:hover { background: #f8fafc; border-color: #aeb9ca; }
        .register-page .btn-google:focus-visible { box-shadow: 0 0 0 3px rgba(40, 118, 232, .12); outline: none; }

        .register-page .links {
            align-items: center;
            color: #64748b;
            font-size: 14px;
            margin-top: 16px;
        }

        .register-page .links span { white-space: nowrap; }
        .register-page .links a { color: #4777b9; text-decoration: none; }
        .register-page .links a:hover { color: #1f5eb8; text-decoration: underline; text-underline-offset: 3px; }
        .register-page .back-home { align-items: center; display: inline-flex; gap: 5px; }

        @media (max-width: 520px) {
            body.register-page { align-items: flex-start; padding: 16px; }
            .register-page .auth-box {
                max-width: none;
                padding: 24px 22px;
                width: 100%;
            }
            .register-page .links { align-items: flex-start; flex-direction: column; }
        }
    </style>
@endpush

@section('content')
    <h1>Create Account</h1>
    <p class="muted">Join Sala Code with a normal user account. Admin roles are managed later by the Super Admin.</p>

    <form action="{{ route('register.post') }}" method="POST">
        @csrf

        <label for="name">Full name</label>
        <input id="name" class="@error('name') is-invalid @enderror" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" @error('name') aria-invalid="true" aria-describedby="nameError" @enderror>
        @error('name')
            <p class="field-error" id="nameError">{{ $message }}</p>
        @enderror

        <label for="email">Email</label>
        <input id="email" class="@error('email') is-invalid @enderror" type="email" name="email" value="{{ old('email') }}" required autocomplete="email" @error('email') aria-invalid="true" aria-describedby="emailError" @enderror>
        @error('email')
            <p class="field-error" id="emailError">{{ $message }}</p>
        @enderror

        <label for="password">Password</label>
        <div class="password-field">
            <input id="password" class="@error('password') is-invalid @enderror" type="password" name="password" required autocomplete="new-password" aria-describedby="passwordRequirements @error('password') passwordError @enderror">
            <button class="password-toggle" type="button" data-password-toggle="password" aria-label="Show password" aria-controls="password" aria-pressed="false"><i class="fa-solid fa-eye" aria-hidden="true"></i></button>
        </div>
        <small class="password-helper" id="passwordRequirements">At least 8 characters with uppercase, lowercase, and a number.</small>
        @error('password')
            <p class="field-error" id="passwordError">{{ $message }}</p>
        @enderror

        <label for="password_confirmation">Confirm password</label>
        <div class="password-field">
            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" aria-describedby="passwordMatchError">
            <button class="password-toggle" type="button" data-password-toggle="password_confirmation" aria-label="Show confirm password" aria-controls="password_confirmation" aria-pressed="false"><i class="fa-solid fa-eye" aria-hidden="true"></i></button>
        </div>
        <p class="password-match-error" id="passwordMatchError" role="status" hidden>Passwords do not match.</p>

        <button class="btn btn-primary" type="submit">Register</button>
    </form>

    @if(($systemSettings['enable_google_login'] ?? true))
        <a class="btn btn-google" href="{{ route('google.redirect') }}"><i class="fa-brands fa-google" aria-hidden="true"></i><span>Continue with Google</span></a>
    @endif

    <div class="links">
        <span>Already have an account? <a href="{{ route('login') }}">Log in</a></span>
        <a class="back-home" href="{{ route('home') }}"><i class="fa-solid fa-arrow-left" aria-hidden="true"></i><span>Back home</span></a>
    </div>
@endsection

@push('scripts')
    <script>
        (() => {
            document.querySelectorAll('[data-password-toggle]').forEach((toggle) => {
                const input = document.getElementById(toggle.dataset.passwordToggle);
                const icon = toggle.querySelector('i');
                if (!input || !icon) return;

                toggle.addEventListener('click', () => {
                    const showing = input.type === 'text';
                    input.type = showing ? 'password' : 'text';
                    icon.classList.toggle('fa-eye', showing);
                    icon.classList.toggle('fa-eye-slash', !showing);
                    toggle.setAttribute('aria-label', showing ? `Show ${input.id === 'password' ? 'password' : 'confirm password'}` : `Hide ${input.id === 'password' ? 'password' : 'confirm password'}`);
                    toggle.setAttribute('aria-pressed', showing ? 'false' : 'true');
                    input.focus();
                });
            });

            const password = document.getElementById('password');
            const confirmation = document.getElementById('password_confirmation');
            const matchError = document.getElementById('passwordMatchError');

            const updatePasswordMatch = () => {
                const mismatched = confirmation.value.length > 0 && password.value !== confirmation.value;
                matchError.hidden = !mismatched;
                confirmation.classList.toggle('is-invalid', mismatched);
                confirmation.setAttribute('aria-invalid', mismatched ? 'true' : 'false');
            };

            password.addEventListener('input', updatePasswordMatch);
            confirmation.addEventListener('input', updatePasswordMatch);
            confirmation.addEventListener('blur', updatePasswordMatch);
        })();
    </script>
@endpush
