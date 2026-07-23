<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Authentication') - Sala Code</title>
    <link rel="shortcut icon" href="{{ asset('assets/images/SalaCode-Logo.png') }}" type="image/x-icon">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        body {
            min-height: 100vh;
            background: #eef4ff;
            color: #111827;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }

        .auth-box {
            width: 100%;
            max-width: 440px;
            background: #ffffff;
            border: 1px solid #dbe4f0;
            border-radius: 8px;
            box-shadow: 0 14px 34px rgba(15, 23, 42, 0.12);
            padding: 30px;
        }

        .brand {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-bottom: 20px;
            color: #1f6fe5;
            font-size: 22px;
            font-weight: 700;
        }

        .brand img {
            width: 42px;
            height: 42px;
            border-radius: 50%;
        }

        h1 {
            font-size: 24px;
            margin-bottom: 8px;
            text-align: center;
        }

        .muted {
            color: #64748b;
            line-height: 1.5;
            margin-bottom: 20px;
            text-align: center;
        }

        label {
            display: block;
            font-weight: 700;
            margin: 14px 0 6px;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            padding: 12px 14px;
            font-size: 15px;
        }

        input:focus {
            border-color: #1f6fe5;
            outline: 3px solid rgba(31, 111, 229, 0.16);
        }

        .checkbox-row {
            display: flex;
            align-items: center;
            gap: 8px;
            margin: 14px 0;
            color: #334155;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            border: 0;
            border-radius: 6px;
            padding: 12px 16px;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
        }

        .btn-primary {
            background: #1f6fe5;
            color: #ffffff;
        }

        .btn-google {
            background: #ffffff;
            color: #111827;
            border: 1px solid #cbd5e1;
            margin-top: 12px;
        }

        .links {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            margin-top: 18px;
            font-size: 14px;
        }

        a {
            color: #1f6fe5;
        }

        .alert {
            border-radius: 6px;
            padding: 12px 14px;
            margin-bottom: 14px;
            line-height: 1.45;
        }

        .alert-success {
            background: #dcfce7;
            color: #166534;
        }

        .alert-info {
            background: #dbeafe;
            color: #1e3a8a;
        }

        .alert-error {
            background: #fee2e2;
            color: #991b1b;
        }

        .field-error {
            color: #b91c1c;
            font-size: 13px;
            margin-top: 6px;
        }
    </style>
</head>
<body>
    <main class="auth-box">
        <a class="brand" href="{{ route('home') }}">
            <img src="{{ asset('assets/images/SalaCode-Logo.png') }}" alt="Sala Code">
            <span>Sala Code</span>
        </a>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if (session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif

        @if (session('info'))
            <div class="alert alert-info">{{ session('info') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-error">{{ $errors->first() }}</div>
        @endif

        @yield('content')
    </main>
</body>
</html>
