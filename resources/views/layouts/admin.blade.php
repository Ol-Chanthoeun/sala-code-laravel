<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Admin Dashboard</title>

    <style>

        body{
            font-family: Arial;
            background: #f5f5f5;
            margin: 0;
        }

        .sidebar{
            width: 250px;
            height: 100vh;
            background: #111827;
            position: fixed;
            padding: 20px;
        }

        .sidebar h2{
            color: white;
        }

        .sidebar a{
            display: block;
            color: white;
            text-decoration: none;
            margin: 15px 0;
        }

        .content{
            margin-left: 270px;
            padding: 20px;
        }

        table{
            width: 100%;
            border-collapse: collapse;
            background: white;
        }

        table th,
        table td{
            border: 1px solid #ddd;
            padding: 12px;
        }

        table th{
            background: #0ea5e9;
            color: white;
        }

    </style>

</head>

<body>

    <div class="sidebar">

        <h2>Sala Code</h2>

        <a href="{{ route('admin.dashboard') }}">
            Dashboard
        </a>

        <a href="{{ route('admin.contacts') }}">
            Contact Messages
        </a>

    </div>

    <div class="content">

        @yield('content')

    </div>

</body>

</html>