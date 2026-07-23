<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Dashboard - @yield('title', 'Sala Code')</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel="stylesheet" href="{{ asset('assets/css/admin.css') }}">

    <style>
        :root { --system-primary: {{ $systemSettings['primary_color'] ?? '#1f6fe5' }}; --system-secondary: {{ $systemSettings['secondary_color'] ?? '#4f46e5' }}; }
        .action-btn { background: var(--system-secondary); }
        .sidebar-nav ul li:hover a, .sidebar-nav ul li.active a { border-left-color: var(--system-secondary); }
    </style>

    @stack('styles')
</head>

<body>

    <div class="admin-container">

        <aside class="sidebar">
            <div class="sidebar-header">
                <div class="logo">
                    <img src="{{ !empty($systemSettings['website_logo']) ? Storage::url($systemSettings['website_logo']) : asset('assets/images/SalaCode-Logo.png') }}" alt="SALA CODE" style="width:28px;height:28px;object-fit:contain;flex-shrink:0;">
                    <span>{{ $systemSettings['website_name'] ?? 'Sala Code' }}</span>
                </div>
                <small>ADMIN PANEL</small>
            </div>

            <nav class="sidebar-nav">
                @php
                    $currentUser = auth()->user();
                    $canManageContent = in_array($currentUser?->role, ['admin', 'super_admin'], true);
                    $isSuperAdmin = $currentUser?->role === 'super_admin';
                @endphp

                <ul>
                    <li class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <a href="{{ route('admin.dashboard') }}">
                            <i class="fas fa-home"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>

                    @if($canManageContent)
                        <li class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.users.index') }}">
                                <i class="fas fa-users"></i>
                                <span>Manage Users</span>
                            </a>
                        </li>

                        @if($isSuperAdmin)
                            <li class="{{ request()->routeIs('admin.admins.*') ? 'active' : '' }}">
                                <a href="{{ route('admin.admins.index') }}">
                                    <i class="fas fa-user-shield"></i>
                                    <span>Admin Management</span>
                                </a>
                            </li>
                        @endif

                        <li class="{{ request()->routeIs('admin.courses.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.courses.index') }}">
                                <i class="fas fa-book"></i>
                                <span>Courses</span>
                            </a>
                        </li>

                        <li class="{{ request()->routeIs('admin.sections.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.sections.index') }}">
                                <i class="fas fa-layer-group"></i>
                                <span>Course Sections</span>
                            </a>
                        </li>

                        <li class="{{ request()->routeIs('admin.lessons.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.lessons.index') }}">
                                <i class="fas fa-file-code"></i>
                                <span>Lessons</span>
                            </a>
                        </li>

                        <li class="{{ request()->routeIs('admin.examples.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.examples.index') }}">
                                <i class="fas fa-code"></i>
                                <span>Code Examples</span>
                            </a>
                        </li>

                        <li class="{{ request()->routeIs('admin.videos.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.videos.index') }}">
                                <i class="fas fa-video"></i>
                                <span>Videos</span>
                            </a>
                        </li>

                        <li class="{{ request()->routeIs('admin.quizzes.*') || request()->routeIs('admin.tests.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.quizzes.index') }}">
                                <i class="fas fa-tasks"></i>
                                <span>Quizzes</span>
                            </a>
                        </li>

                        <li class="{{ request()->routeIs('admin.programming-languages.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.programming-languages.index') }}">
                                <i class="fas fa-code-branch"></i>
                                <span>Quiz Languages</span>
                            </a>
                        </li>

                        <li class="{{ request()->routeIs('admin.quiz-categories.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.quiz-categories.index') }}">
                                <i class="fas fa-folder-tree"></i>
                                <span>Quiz Categories</span>
                            </a>
                        </li>

                        <li class="{{ request()->routeIs('admin.quiz-questions.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.quiz-questions.index') }}">
                                <i class="fas fa-circle-question"></i>
                                <span>Quiz Questions</span>
                            </a>
                        </li>

                        <li class="{{ request()->routeIs('admin.contacts') ? 'active' : '' }}">
                            <a href="{{ route('admin.contacts') }}">
                                <i class="fas fa-envelope"></i>
                                <span>Contact Messages</span>
                            </a>
                        </li>
                    @endif

                    @if($isSuperAdmin)
                        <li class="{{ request()->routeIs('admin.activity-logs.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.activity-logs.index') }}">
                                <i class="fas fa-clock-rotate-left"></i>
                                <span>Activity Logs</span>
                            </a>
                        </li>

                        <li class="{{ request()->routeIs('admin.system-settings.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.system-settings.index') }}">
                                <i class="fas fa-cogs"></i>
                                <span>System Settings</span>
                            </a>
                        </li>
                    @endif

                    <li class="{{ request()->routeIs('profile.*') ? 'active' : '' }}">
                        <a href="{{ route('profile.show') }}">
                            <i class="fas fa-id-card"></i>
                            <span>Profile</span>
                        </a>
                    </li>
                </ul>
            </nav>

            <div class="sidebar-footer">
                <p>Sala Code</p>
                <small>© 2026 All rights reserved.</small>
            </div>
        </aside>

        <main class="main-content">

            <header class="topbar">
                <div class="topbar-left">
                    <button class="sidebar-toggle" id="sidebarToggle">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h2>@yield('page-title', 'Dashboard')</h2>
                </div>

                <div class="topbar-right">
                    <div class="breadcrumb">
                        <a href="{{ route('admin.dashboard') }}">Home</a>
                        <span>/</span>
                        <span>@yield('breadcrumb', 'Dashboard')</span>
                    </div>

                    <div class="user-menu">
                        @php
                            $adminUser = auth()->user();
                            $avatarFallback = 'https://ui-avatars.com/api/?name='.urlencode($adminUser?->name ?? 'Admin').'&background=4F46E5&color=fff';
                            $adminAvatar = $adminUser?->avatar;
                            $adminAvatarUrl = $adminAvatar
                                ? (Str::startsWith($adminAvatar, ['http://', 'https://']) ? $adminAvatar : Storage::url($adminAvatar))
                                : $avatarFallback;
                        @endphp
                        <img src="{{ $adminAvatarUrl }}" alt="{{ $adminUser?->name ?? 'Admin' }}" onerror="this.onerror=null;this.src='{{ $avatarFallback }}';">

                        <span>{{ $adminUser?->name ?? 'Admin' }}</span>

                        <form action="{{ route('logout') }}" method="POST" style="margin:0;">
                            @csrf
                            <button type="submit" style="
            background:#dc3545;
            color:white;
            border:none;
            padding:8px 12px;
            border-radius:6px;
            cursor:pointer;
        ">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <div class="content-wrapper">
                @yield('content')
            </div>

        </main>

    </div>

    <script>
        document.getElementById('sidebarToggle')?.addEventListener('click', function () {
            document.querySelector('.admin-container').classList.toggle('sidebar-collapsed');
        });
    </script>

    @stack('scripts')
</body>

</html>
