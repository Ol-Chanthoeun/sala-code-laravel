@extends('auth.layout')

@section('title', 'Login')

@section('content')
    <h1>Login</h1>
    <p class="muted">Access your Sala Code profile and continue learning.</p>

    <form action="{{ route('login.post') }}" method="POST">
        @csrf

        <label for="email">Email</label>
        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="email">
        @error('email')
            <p class="field-error">{{ $message }}</p>
        @enderror

        <label for="password">Password</label>
        <input id="password" type="password" name="password" required autocomplete="current-password">
        @error('password')
            <p class="field-error">{{ $message }}</p>
        @enderror

        <label class="checkbox-row">
            <input type="checkbox" name="remember" value="1">
            <span>Remember me</span>
        </label>

        <button class="btn btn-primary" type="submit">Login</button>
    </form>

    @if(($systemSettings['enable_google_login'] ?? true))
        <a class="btn btn-google" href="{{ route('google.redirect') }}">Login with Google</a>
    @endif

    <div class="links">
        @if(($systemSettings['enable_forgot_password'] ?? true))<a href="{{ route('password.request') }}">Forgot password?</a>@endif
        @if(($systemSettings['enable_registration'] ?? true))<a href="{{ route('register') }}">Create account</a>@endif
    </div>
@endsection
