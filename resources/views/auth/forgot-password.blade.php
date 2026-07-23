@extends('auth.layout')

@section('title', 'Forgot Password')

@section('content')
    <h1>Reset Password</h1>
    <p class="muted">Enter your email address and Sala Code will send a secure password reset link.</p>

    <form action="{{ route('password.email') }}" method="POST">
        @csrf

        <label for="email">Email</label>
        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="email">
        @error('email')
            <p class="field-error">{{ $message }}</p>
        @enderror

        <button class="btn btn-primary" type="submit" style="margin-top:18px;">Send Reset Link</button>
    </form>

    <div class="links">
        <a href="{{ route('login') }}">Back to login</a>
        <a href="{{ route('register') }}">Create account</a>
    </div>
@endsection
