<header class="navbar">
    <a class="logo" href="{{ route('home') }}" aria-label="Sala Code home">
        <img src="{{ !empty($systemSettings['website_logo']) ? Storage::url($systemSettings['website_logo']) : asset('assets/images/SalaCode-Logo.png') }}" alt="{{ $systemSettings['website_name'] ?? 'Sala Code' }}">
        <span>{{ $systemSettings['website_name'] ?? 'Sala Code' }}</span>
    </a>

    <button class="menu-btn" id="menuBtn" type="button" aria-label="Open menu" aria-expanded="false">
        <i class="bx bx-menu"></i>
    </button>

    <nav class="nav-links" id="navLinks" aria-label="Main navigation">
        <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">
            <i class="bx bx-home"></i>
            <span>Home</span>
        </a>

        <a href="{{ route('courses') }}" class="{{ request()->routeIs('courses') || request()->routeIs('courses.*') || request()->routeIs('c_programming') ? 'active' : '' }}">
            <i class="bx bx-book-content"></i>
            <span>Courses</span>
        </a>

        <a href="{{ route('videos') }}" class="{{ request()->routeIs('videos') ? 'active' : '' }}">
            <i class="bx bx-play-circle"></i>
            <span>Videos</span>
        </a>

        <a href="{{ route('test') }}" class="{{ request()->routeIs('test') || request()->routeIs('quiz.course') ? 'active' : '' }}">
            <i class="bx bx-check-circle"></i>
            <span>Tests</span>
        </a>

        <a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'active' : '' }}">
            <i class="bx bx-user"></i>
            <span>About</span>
        </a>

        <a href="{{ route('contact') }}" class="{{ request()->routeIs('contact') ? 'active' : '' }}">
            <i class="bx bx-phone"></i>
            <span>Contact</span>
        </a>

        @guest
            @if(($systemSettings['enable_registration'] ?? true))
                <a href="{{ route('register') }}" class="nav-auth-link {{ request()->routeIs('register') ? 'active' : '' }}">
                    <i class="bx bx-user-plus"></i>
                    <span>Register</span>
                </a>
            @endif

            <a href="{{ route('login') }}" class="nav-auth-link {{ request()->routeIs('login') ? 'active' : '' }}">
                <i class="bx bx-log-in"></i>
                <span>Login</span>
            </a>

        @else
            <span class="nav-user-name">
                <i class="bx bx-user-circle"></i>
                <span>{{ auth()->user()->name }}</span>
            </span>

            <a href="{{ route('profile.show') }}" class="nav-auth-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                <i class="bx bx-id-card"></i>
                <span>Profile</span>
            </a>

            <form class="nav-logout-form" action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit">
                    <i class="bx bx-log-out"></i>
                    <span>Logout</span>
                </button>
            </form>
        @endguest
    </nav>
</header>
