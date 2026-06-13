<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sala Code</title>

    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="shortcut icon" href="{{ asset('assets/images/SalaCode-Logo.png') }}" type="image/x-icon">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

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