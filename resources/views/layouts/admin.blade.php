<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Dashboard - @yield('title', 'Sala Code')</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Noto+Sans+Khmer:wght@400;500;600;700&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel="stylesheet" href="{{ asset('assets/css/admin.css') }}">

    <style>
        :root { --system-primary: {{ $systemSettings['primary_color'] ?? '#1f6fe5' }}; --system-secondary: {{ $systemSettings['secondary_color'] ?? '#4f46e5' }}; }
        .action-btn { background: var(--system-secondary); }
        .sidebar-link:hover, .sidebar-link.active, .sidebar-group-toggle:hover, .sidebar-group.is-active > .sidebar-group-toggle { border-left-color: var(--system-secondary); }
    </style>

    @stack('styles')
</head>

<body class="{{ request()->boolean('modal') ? 'admin-modal-document' : '' }}">

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

                <div class="sidebar-section">
                    <p class="sidebar-section-label">Main</p>
                    <a class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}"><i class="fas fa-house"></i><span>Dashboard</span></a>
                </div>

                @if($canManageContent)
                    @php
                        $usersOpen = request()->routeIs('admin.users.*') || request()->routeIs('admin.admins.*');
                    @endphp
                    <div class="sidebar-section">
                        <p class="sidebar-section-label">User Management</p>
                        <div class="sidebar-group {{ $usersOpen ? 'is-active is-open' : '' }}">
                            <button class="sidebar-group-toggle" type="button" aria-expanded="{{ $usersOpen ? 'true' : 'false' }}"><i class="fas fa-users"></i><span>Users</span><i class="fas fa-chevron-down sidebar-chevron"></i></button>
                            <div class="sidebar-submenu"><ul>
                                <li><a class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">Manage Users</a></li>
                                @if($isSuperAdmin)
                                    <li><a class="{{ request()->routeIs('admin.admins.*') ? 'active' : '' }}" href="{{ route('admin.admins.index') }}">Admin Management</a></li>
                                @endif
                            </ul></div>
                        </div>
                    </div>

                    @php
                        $coursesOpen = request()->routeIs('admin.courses.*') || request()->routeIs('admin.sections.*') || request()->routeIs('admin.lessons.*') || request()->routeIs('admin.examples.*');
                        $mediaOpen = request()->routeIs('admin.videos.*') || request()->routeIs('admin.video-playlists.*');
                    @endphp
                    <div class="sidebar-section">
                        <p class="sidebar-section-label">Content Management</p>
                        <div class="sidebar-group {{ $coursesOpen ? 'is-active is-open' : '' }}">
                            <button class="sidebar-group-toggle" type="button" aria-expanded="{{ $coursesOpen ? 'true' : 'false' }}"><i class="fas fa-book-open"></i><span>Course Management</span><i class="fas fa-chevron-down sidebar-chevron"></i></button>
                            <div class="sidebar-submenu"><ul>
                                <li><a class="{{ request()->routeIs('admin.courses.*') ? 'active' : '' }}" href="{{ route('admin.courses.index') }}">Courses</a></li>
                                <li><a class="{{ request()->routeIs('admin.sections.*') ? 'active' : '' }}" href="{{ route('admin.sections.index') }}">Course Sections</a></li>
                                <li><a class="{{ request()->routeIs('admin.lessons.*') ? 'active' : '' }}" href="{{ route('admin.lessons.index') }}">Lessons</a></li>
                                <li><a class="{{ request()->routeIs('admin.examples.*') ? 'active' : '' }}" href="{{ route('admin.examples.index') }}">Code Examples</a></li>
                            </ul></div>
                        </div>
                        <div class="sidebar-group {{ $mediaOpen ? 'is-active is-open' : '' }}">
                            <button class="sidebar-group-toggle" type="button" aria-expanded="{{ $mediaOpen ? 'true' : 'false' }}"><i class="fas fa-photo-film"></i><span>Media Library</span><i class="fas fa-chevron-down sidebar-chevron"></i></button>
                            <div class="sidebar-submenu"><ul>
                                <li><a class="{{ request()->routeIs('admin.videos.*') ? 'active' : '' }}" href="{{ route('admin.videos.index') }}">Videos</a></li>
                                <li><a class="{{ request()->routeIs('admin.video-playlists.*') ? 'active' : '' }}" href="{{ route('admin.video-playlists.index') }}">Video Playlists</a></li>
                            </ul></div>
                        </div>
                    </div>

                    @php
                        $quizzesOpen = request()->routeIs('admin.quizzes.*') || request()->routeIs('admin.tests.*') || request()->routeIs('admin.quiz-questions.*') || request()->routeIs('admin.quiz-categories.*') || request()->routeIs('admin.programming-languages.*');
                    @endphp
                    <div class="sidebar-section">
                        <p class="sidebar-section-label">Assessment</p>
                        <div class="sidebar-group {{ $quizzesOpen ? 'is-active is-open' : '' }}">
                            <button class="sidebar-group-toggle" type="button" aria-expanded="{{ $quizzesOpen ? 'true' : 'false' }}"><i class="fas fa-list-check"></i><span>Quizzes</span><i class="fas fa-chevron-down sidebar-chevron"></i></button>
                            <div class="sidebar-submenu"><ul>
                                <li><a class="{{ request()->routeIs('admin.quizzes.*') || request()->routeIs('admin.tests.*') ? 'active' : '' }}" href="{{ route('admin.quizzes.index') }}">All Quizzes</a></li>
                                <li><a class="{{ request()->routeIs('admin.quiz-questions.*') ? 'active' : '' }}" href="{{ route('admin.quiz-questions.index') }}">Quiz Questions</a></li>
                                <li><a class="{{ request()->routeIs('admin.quiz-categories.*') ? 'active' : '' }}" href="{{ route('admin.quiz-categories.index') }}">Quiz Categories</a></li>
                                <li><a class="{{ request()->routeIs('admin.programming-languages.*') ? 'active' : '' }}" href="{{ route('admin.programming-languages.index') }}">Quiz Languages</a></li>
                            </ul></div>
                        </div>
                    </div>

                    <div class="sidebar-section">
                        <p class="sidebar-section-label">Communication</p>
                        <a class="sidebar-link {{ request()->routeIs('admin.contacts') ? 'active' : '' }}" href="{{ route('admin.contacts') }}"><i class="fas fa-envelope"></i><span>Contact Messages</span></a>
                    </div>

                    @php
                        $securityOpen = request()->routeIs('admin.activity-logs.*') || request()->routeIs('admin.reports.*');
                    @endphp
                    <div class="sidebar-section">
                        <p class="sidebar-section-label">System &amp; Security</p>
                        <div class="sidebar-group {{ $securityOpen ? 'is-active is-open' : '' }}">
                            <button class="sidebar-group-toggle" type="button" aria-expanded="{{ $securityOpen ? 'true' : 'false' }}"><i class="fas fa-shield-halved"></i><span>Security &amp; Logs</span><i class="fas fa-chevron-down sidebar-chevron"></i></button>
                            <div class="sidebar-submenu"><ul>
                                @if($isSuperAdmin)
                                    <li><a class="{{ request()->routeIs('admin.activity-logs.*') ? 'active' : '' }}" href="{{ route('admin.activity-logs.index') }}">Activity Logs</a></li>
                                @endif
                                <li><a class="{{ request()->routeIs('admin.reports.*') ? 'active' : '' }}" href="{{ route('admin.reports.index') }}">Reports &amp; Export</a></li>
                            </ul></div>
                        </div>
                    </div>
                @endif
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

                    <div class="admin-user-menu">
                        @php
                            $adminUser = auth()->user();
                        @endphp
                        <button class="admin-user-trigger" id="adminUserMenuTrigger" type="button" aria-expanded="false" aria-controls="adminUserDropdown">
                            <img src="{{ $adminUser->avatar_url }}" alt="" onerror="this.onerror=null;this.src='{{ $adminUser->default_avatar_url }}';">
                            <span>{{ $adminUser->name }}</span><i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="admin-user-dropdown" id="adminUserDropdown" hidden>
                            <div class="admin-user-dropdown__identity"><strong>{{ $adminUser->name }}</strong><small>{{ $adminUser->email }}</small></div>
                            <a href="{{ route('profile.show') }}"><i class="fas fa-id-card"></i>My Profile</a>
                            @if($isSuperAdmin)
                                <a href="{{ route('admin.system-settings.index') }}"><i class="fas fa-gear"></i>System Settings</a>
                            @endif
                            <a href="{{ route('home') }}"><i class="fas fa-arrow-up-right-from-square"></i>Go to Website</a>
                            <form action="{{ route('logout') }}" method="POST">@csrf<button type="submit"><i class="fas fa-right-from-bracket"></i>Logout</button></form>
                        </div>
                    </div>
                </div>
            </header>

            <div class="content-wrapper">
                @yield('content')
            </div>

        </main>

    </div>

    <dialog class="admin-confirm-dialog" id="adminDestructiveDialog" data-tone="danger" aria-labelledby="adminDestructiveDialogTitle">
        <div class="admin-confirm-dialog__panel">
            <div class="admin-confirm-dialog__header">
                <div class="admin-confirm-dialog__title"><i class="fas fa-triangle-exclamation" aria-hidden="true"></i><h3 id="adminDestructiveDialogTitle">Confirm Delete</h3></div>
                <button class="admin-confirm-dialog__close" id="closeAdminDestructive" type="button" aria-label="Close">&times;</button>
            </div>
            <p id="adminDestructiveMessage"></p>
            <p class="admin-confirm-dialog__note">This action cannot be undone.</p>
            <dl><div><dt>Item</dt><dd id="adminDestructiveItem"></dd></div></dl>
            <div class="admin-confirm-dialog__actions">
                <button class="admin-confirm-dialog__cancel" id="cancelAdminDestructive" type="button">Cancel</button>
                <button class="admin-confirm-dialog__confirm" id="confirmAdminDestructive" type="button">Delete</button>
            </div>
        </div>
    </dialog>

    <dialog class="admin-confirm-dialog admin-form-frame-dialog" id="adminFormFrameDialog" aria-labelledby="adminFormFrameTitle">
        <div class="admin-confirm-dialog__panel admin-form-frame-dialog__panel">
            <div class="admin-confirm-dialog__header">
                <div class="admin-confirm-dialog__title"><i class="fas fa-pen-to-square" aria-hidden="true"></i><h3 id="adminFormFrameTitle">Manage Record</h3></div>
                <button class="admin-confirm-dialog__close" id="closeAdminFormFrame" type="button" aria-label="Close">&times;</button>
            </div>
            <iframe id="adminFormFrame" title="Admin form"></iframe>
        </div>
    </dialog>

    <script>
        document.getElementById('sidebarToggle')?.addEventListener('click', function () {
            document.querySelector('.admin-container').classList.toggle('sidebar-collapsed');
        });

        (() => {
            const groups = [...document.querySelectorAll('.sidebar-group')];
            groups.forEach((group) => {
                const toggle = group.querySelector('.sidebar-group-toggle');
                toggle.addEventListener('click', () => {
                    const opening = !group.classList.contains('is-open');
                    groups.forEach((other) => {
                        if (other === group) return;
                        other.classList.remove('is-open');
                        other.querySelector('.sidebar-group-toggle').setAttribute('aria-expanded', 'false');
                    });
                    group.classList.toggle('is-open', opening);
                    toggle.setAttribute('aria-expanded', opening ? 'true' : 'false');
                });
            });
        })();

        (() => {
            const menu = document.querySelector('.admin-user-menu');
            const trigger = document.getElementById('adminUserMenuTrigger');
            const dropdown = document.getElementById('adminUserDropdown');
            if (!menu || !trigger || !dropdown) return;
            const close = () => { dropdown.hidden = true; trigger.setAttribute('aria-expanded', 'false'); };
            trigger.addEventListener('click', () => {
                const opening = dropdown.hidden;
                dropdown.hidden = !opening;
                trigger.setAttribute('aria-expanded', opening ? 'true' : 'false');
            });
            document.addEventListener('click', event => { if (!menu.contains(event.target)) close(); });
            document.addEventListener('keydown', event => { if (event.key === 'Escape') { close(); trigger.focus(); } });
        })();

        (() => {
            const dialog = document.getElementById('adminDestructiveDialog');
            let pendingForm = null;

            document.querySelectorAll('.admin-destructive-form').forEach((form) => form.addEventListener('submit', (event) => {
                event.preventDefault();
                pendingForm = form;
                document.getElementById('adminDestructiveDialogTitle').textContent = form.dataset.confirmTitle || 'Confirm Delete';
                document.getElementById('adminDestructiveMessage').textContent = form.dataset.confirmMessage || 'Are you sure you want to delete this item?';
                document.getElementById('adminDestructiveItem').textContent = form.dataset.confirmItem || 'Selected item';
                document.getElementById('confirmAdminDestructive').textContent = form.dataset.confirmLabel || 'Delete';
                dialog.showModal();
                document.body.classList.add('admin-modal-open');
            }));

            document.getElementById('cancelAdminDestructive').addEventListener('click', () => dialog.close());
            document.getElementById('closeAdminDestructive').addEventListener('click', () => dialog.close());
            dialog.addEventListener('click', (event) => { if (event.target === dialog) dialog.close(); });
            dialog.addEventListener('close', () => {
                pendingForm = null;
                document.body.classList.remove('admin-modal-open');
            });
            document.getElementById('confirmAdminDestructive').addEventListener('click', () => {
                const form = pendingForm;
                dialog.close();
                if (form) HTMLFormElement.prototype.submit.call(form);
            });
        })();

        (() => {
            const dialog = document.getElementById('adminFormFrameDialog');
            const frame = document.getElementById('adminFormFrame');
            let formPath = null;

            document.querySelectorAll('.admin-modal-form-link, a[href*="/create"], a[href$="/edit"]').forEach((link) => link.addEventListener('click', (event) => {
                event.preventDefault();
                const url = new URL(link.href, window.location.href);
                url.searchParams.set('modal', '1');
                formPath = url.pathname;
                document.getElementById('adminFormFrameTitle').textContent = link.dataset.modalTitle || 'Manage Record';
                frame.src = url.href;
                dialog.showModal();
                document.body.classList.add('admin-modal-open');
            }));

            frame.addEventListener('load', () => {
                if (!formPath || frame.contentWindow.location.href === 'about:blank') return;
                if (frame.contentWindow.location.pathname !== formPath) window.location.reload();
            });
            document.getElementById('closeAdminFormFrame').addEventListener('click', () => dialog.close());
            dialog.addEventListener('click', (event) => { if (event.target === dialog) dialog.close(); });
            dialog.addEventListener('close', () => {
                formPath = null;
                frame.src = 'about:blank';
                document.body.classList.remove('admin-modal-open');
            });
        })();
    </script>

    @stack('scripts')
</body>

</html>
