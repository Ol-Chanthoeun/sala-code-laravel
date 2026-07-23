@extends('auth.layout')

@section('title', 'Register')

@section('content')
    <h1>Create Account</h1>
    <p class="muted">Join Sala Code with a normal user account. Admin roles are managed later by the Super Admin.</p>

    <form action="{{ route('register.post') }}" method="POST">
        @csrf

        <label for="name">Full name</label>
        <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name">
        @error('name')
            <p class="field-error">{{ $message }}</p>
        @enderror

        <label for="email">Email</label>
        <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email">
        @error('email')
            <p class="field-error">{{ $message }}</p>
        @enderror

        <label for="password">Password</label>
        <input id="password" type="password" name="password" required autocomplete="new-password">
        @error('password')
            <p class="field-error">{{ $message }}</p>
        @enderror

        <label for="password_confirmation">Confirm password</label>
        <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password">

        <button class="btn btn-primary" type="submit" style="margin-top:18px;">Register</button>
    </form>

    @if(($systemSettings['enable_google_login'] ?? true))
        <a class="btn btn-google" href="{{ route('google.redirect') }}">Register with Google</a>
    @endif

    <div class="links">
        <a href="{{ route('login') }}">Already have an account?</a>
        <a href="{{ route('home') }}">Back home</a>
    </div>
@endsection
