<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $systemSettings['website_name'] ?? 'Sala Code' }}</title>
    @if(!empty($systemSettings['website_description']))<meta name="description" content="{{ $systemSettings['website_description'] }}">@endif

    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="shortcut icon" href="{{ !empty($systemSettings['favicon']) ? Storage::url($systemSettings['favicon']) : asset('assets/images/SalaCode-Logo.png') }}" type="image/x-icon">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <style>
        :root { --system-primary: {{ $systemSettings['primary_color'] ?? '#1f6fe5' }}; --system-secondary: {{ $systemSettings['secondary_color'] ?? '#4f46e5' }}; }
        .navbar { background: var(--system-primary); }
    </style>

    @stack('styles')
</head>
<body>

    @include('frontend.partials.header')

    @yield('content')

    @include('frontend.partials.footer')

    <script src="{{ asset('assets/js/main.js') }}"></script>
    @stack('scripts')

</body>
</html>
