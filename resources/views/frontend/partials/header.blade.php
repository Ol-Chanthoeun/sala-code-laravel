<header class="navbar">

    <div class="logo">
        <img src="{{ asset('assets/images/SalaCode-Logo.png') }}" alt="LogoSalaCode">
        <span>សាលាកូដ</span>
    </div>

    <button class="menu-btn"
        id="menuBtn"
        aria-label="Open menu"
        aria-expanded="false">

        ☰

    </button>

    <nav class="nav-links" id="navLinks">

        <a href="{{ route('home') }}"
            class="{{ request()->routeIs('home') ? 'active' : '' }}">

            <i class='bx bx-home'></i>
            ទំព័រដើម

        </a>

        <a href="{{ route('courses') }}"
            class="{{ request()->routeIs('courses') || request()->routeIs('c_programming') ? 'active' : '' }}">

            <i class='bx bx-book-content'></i>
            ថ្នាក់រៀន

        </a>

        <a href="{{ route('videos') }}"
            class="{{ request()->routeIs('videos') ? 'active' : '' }}">

            <i class='bx bx-play-circle'></i>
            វិដេអូ

        </a>

        <a href="{{ route('test') }}"
            class="{{ request()->routeIs('test') || request()->routeIs('quiz') ? 'active' : '' }}">

            <i class='bx bx-check-circle'></i>
            ការតេស្ត

        </a>

        <a href="{{ route('about') }}"
            class="{{ request()->routeIs('about') ? 'active' : '' }}">

            <i class='bx bx-user'></i>
            អំពីយើង

        </a>

        <a href="{{ route('contact') }}"
            class="{{ request()->routeIs('contact') ? 'active' : '' }}">

            <i class='bx bx-phone'></i>
            ទំនាក់ទំនង

        </a>

    </nav>

</header>