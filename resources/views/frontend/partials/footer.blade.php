<footer class="site-footer">
    <div class="footer-top">
        <section class="footer-col footer-brand" aria-labelledby="footer-brand-title">
            <div class="footer-brand__heading">
                <img src="{{ asset('assets/images/SalaCode-Logo.png') }}" alt="Sala Code logo">
                <h3 id="footer-brand-title">Sala Code</h3>
            </div>
            <p>ជាគេហទំព័រអប់រំ ITE សម្រាប់សិស្ស និងអ្នកចាប់ផ្តើមសិក្សាព័ត៌មានវិទ្យា។</p>
        </section>

        <section class="footer-col center" aria-labelledby="footer-social-title">
            <h3 id="footer-social-title">បណ្ដាញសង្គម</h3>
            <div class="social-icons">
                <a href="{{ $systemSettings['facebook_url'] ?: 'https://web.facebook.com/profile.php?id=61586520855760' }}" target="_blank" rel="noopener noreferrer" aria-label="Facebook"><i class="fa-brands fa-facebook-f" aria-hidden="true"></i></a>
                <a href="{{ $systemSettings['telegram_url'] ?: 'https://t.me/SalaCode007' }}" target="_blank" rel="noopener noreferrer" aria-label="Telegram"><i class="fa-brands fa-telegram" aria-hidden="true"></i></a>
                <a href="#" aria-label="TikTok"><i class="fa-brands fa-tiktok" aria-hidden="true"></i></a>
            </div>
            <div class="footer-logo">
                <img src="{{ asset('assets/images/Rupp_logo.png') }}" alt="Royal University of Phnom Penh logo">
                <p class="footer-name">RUPP</p>
            </div>
        </section>

        <section class="footer-col footer-location" aria-labelledby="footer-location-title">
            <h3 id="footer-location-title">ទីតាំង</h3>
            <div class="footer-location__row">
                <i class="fa-solid fa-location-dot" aria-hidden="true"></i>
                <p>សាកលវិទ្យាល័យភូមិន្ទភ្នំពេញ<br><span>Phnom Penh, Cambodia</span></p>
            </div>
        </section>

        <nav class="footer-col footer-quick-links" aria-labelledby="footer-links-title">
            <h3 id="footer-links-title">Quick Links</h3>
            <a href="{{ route('about') }}">About Us</a>
            <a href="{{ route('contact') }}">Contact</a>
            <a href="{{ route('courses') }}">Courses</a>
            <a href="{{ route('videos') }}">Videos</a>
        </nav>
    </div>

    <div class="footer-bottom">
        {{ $systemSettings['footer_text'] ?: 'Copyright © 2026 Sala Code. All rights reserved.' }}
    </div>
</footer>
