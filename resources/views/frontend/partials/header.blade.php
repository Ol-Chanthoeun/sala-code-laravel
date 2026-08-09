<header class="navbar">
    <a class="logo" href="{{ route('home') }}" aria-label="Sala Code home">
        <img src="{{ !empty($systemSettings['website_logo']) ? Storage::url($systemSettings['website_logo']) : asset('assets/images/SalaCode-Logo.png') }}" alt="{{ $systemSettings['website_name'] ?? 'Sala Code' }}">
        <span>{{ $systemSettings['website_name'] ?? 'Sala Code' }}</span>
    </a>

    <button class="menu-btn" id="menuBtn" type="button" aria-label="Open menu" aria-controls="navLinks" aria-expanded="false">
        <i class="bx bx-menu" aria-hidden="true"></i>
    </button>

    <nav class="nav-links" id="navLinks" aria-label="Main navigation">
        @auth
            <div class="navbar-search" data-search-url="{{ route('search') }}">
                <label class="sr-only" for="navbarSearch">Search courses, lessons, videos, and tests</label>
                <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>
                <input id="navbarSearch" type="search" placeholder="Search courses, lessons..." autocomplete="off" aria-autocomplete="list" aria-controls="navbarSearchResults" aria-expanded="false">
                <button class="navbar-search__clear" type="button" aria-label="Clear search" hidden><i class="fa-solid fa-xmark" aria-hidden="true"></i></button>
                <div class="navbar-search-results" id="navbarSearchResults" role="listbox" hidden></div>
            </div>
        @endauth

        <div class="nav-primary-links">
            <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">
                <i class="fa-solid fa-house" aria-hidden="true"></i><span>Home</span>
            </a>
            <a href="{{ route('courses') }}" class="{{ request()->routeIs('courses') || request()->routeIs('courses.*') || request()->routeIs('c_programming') ? 'active' : '' }}">
                <i class="fa-solid fa-book-open" aria-hidden="true"></i><span>Courses</span>
            </a>
            <a href="{{ route('videos') }}" class="{{ request()->routeIs('videos') || request()->routeIs('videos.*') ? 'active' : '' }}">
                <i class="fa-solid fa-circle-play" aria-hidden="true"></i><span>Videos</span>
            </a>
            <a href="{{ route('test') }}" class="{{ request()->routeIs('test') || request()->routeIs('quiz.*') ? 'active' : '' }}">
                <i class="fa-solid fa-list-check" aria-hidden="true"></i><span>Tests</span>
            </a>

            @guest
                <a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'active' : '' }}">
                    <i class="fa-solid fa-circle-info" aria-hidden="true"></i><span>About</span>
                </a>
                <a href="{{ route('contact') }}" class="{{ request()->routeIs('contact') ? 'active' : '' }}">
                    <i class="fa-solid fa-phone" aria-hidden="true"></i><span>Contact</span>
                </a>
            @endguest
        </div>

        <div class="nav-account-links">
            @guest
                <span class="nav-section-label">Account</span>
                <a href="{{ route('login') }}" class="nav-auth-link {{ request()->routeIs('login') ? 'active' : '' }}">
                    <i class="fa-solid fa-right-to-bracket" aria-hidden="true"></i><span>Login</span>
                </a>
            @else
                @php
                    $navbarUser = auth()->user();
                    $navbarAvatar = $navbarUser->avatar;
                    $navbarAvatarUrl = $navbarAvatar
                        ? (Str::startsWith($navbarAvatar, ['http://', 'https://']) ? $navbarAvatar : Storage::url($navbarAvatar))
                        : null;
                    $navbarInitials = Str::upper(Str::substr($navbarUser->name, 0, 2));
                    $navbarRole = Str::headline($navbarUser->role);
                @endphp

                <div class="navbar-notifications navbar-popover">
                    <button class="navbar-icon-button navbar-popover-trigger" type="button" aria-label="Notifications" aria-expanded="false" aria-controls="notificationDropdown">
                        <i class="fa-solid fa-bell" aria-hidden="true"></i>
                        <span class="navbar-mobile-row-label">Notifications</span>
                        <i class="fa-solid fa-chevron-right navbar-mobile-row-chevron" aria-hidden="true"></i>
                    </button>
                    <div class="navbar-dropdown notification-dropdown" id="notificationDropdown" hidden>
                        <div class="navbar-dropdown__heading"><strong>Notifications</strong></div>
                        <div class="notification-empty">
                            <i class="fa-regular fa-bell" aria-hidden="true"></i>
                            <span>No notifications yet</span>
                        </div>
                    </div>
                </div>

                <div class="navbar-user-menu navbar-popover">
                    <button class="navbar-user-trigger navbar-popover-trigger" type="button" aria-label="Open account menu for {{ $navbarUser->name }}" aria-expanded="false" aria-controls="accountDropdown">
                        <span class="navbar-avatar" aria-hidden="true">
                            @if($navbarAvatarUrl)
                                <img src="{{ $navbarAvatarUrl }}" alt="">
                            @else
                                {{ $navbarInitials }}
                            @endif
                        </span>
                        <span class="navbar-mobile-row-label">{{ $navbarUser->name }}</span>
                        <i class="fa-solid fa-chevron-down navbar-user-trigger__chevron" aria-hidden="true"></i>
                    </button>

                    <div class="navbar-dropdown account-dropdown" id="accountDropdown" hidden>
                        <div class="account-dropdown__identity">
                            <strong>{{ $navbarUser->name }}</strong>
                            <span>{{ $navbarUser->email }}</span>
                            <small>{{ $navbarRole }}</small>
                        </div>
                        <div class="account-dropdown__actions">
                            @if($navbarUser->isAdmin() || $navbarUser->isSuperAdmin())
                                <a class="account-dropdown__dashboard" href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-gauge-high" aria-hidden="true"></i><span>Admin Dashboard</span></a>
                            @endif
                            <a class="account-dropdown__profile" href="{{ route('profile.show') }}"><i class="fa-solid fa-user" aria-hidden="true"></i><span class="account-profile-desktop">My Profile</span><span class="account-profile-mobile">Profile</span></a>
                        </div>
                        <form class="account-dropdown__logout" action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit"><i class="fa-solid fa-right-from-bracket" aria-hidden="true"></i><span>Logout</span></button>
                        </form>
                    </div>
                </div>

            @endguest
        </div>
    </nav>
</header>
<div class="nav-backdrop" id="navBackdrop" aria-hidden="true"></div>
