@extends('auth.layout')

@section('title', 'Set New Password')

@section('content')
    <h1>Set New Password</h1>
    <p class="muted">Choose a strong password to protect your Sala Code account.</p>

    <form action="{{ route('password.update') }}" method="POST">
        @csrf

        <input type="hidden" name="token" value="{{ $token }}">

        <label for="email">Email</label>
        <input id="email" type="email" name="email" value="{{ old('email', $email) }}" required autocomplete="email">
        @error('email')
            <p class="field-error">{{ $message }}</p>
        @enderror

        <label for="password">New password</label>
        <input id="password" type="password" name="password" required autocomplete="new-password">
        @error('password')
            <p class="field-error">{{ $message }}</p>
        @enderror

        <label for="password_confirmation">Confirm new password</label>
        <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password">

        <button class="btn btn-primary" type="submit" style="margin-top:18px;">Update Password</button>
    </form>
@endsection
