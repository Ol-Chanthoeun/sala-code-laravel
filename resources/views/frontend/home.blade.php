@extends('layouts.frontend')

@section('content')

{{-- Hero section --}}
<section class="hero">
    <div class="hero-left">
        <img src="{{ asset('assets/images/LearnOnline.png') }}" alt="Student">
    </div>

    <div class="hero-right">
        <h1>
            សូមស្វាគមន៍មកកាន់
            <span class="colorShift">សាលាកូដអនឡាញ</span>
        </h1>

        <p class="typing" id="typingText"></p>

        <div class="hero-buttons">
            <button class="btn primary">
                <span>បេសកកម្ម</span>
                <p>បណ្តុះបណ្តាលយុវជនខ្មែរឱ្យមានជំនាញ <br>បច្ចេកវិទ្យាច្បាស់លាស់។</p>
            </button>

            <button class="btn secondary">
                <span>កម្មវិធីសិក្សា</span>
                <p>មានមេរៀនច្រើនសម្រាប់ការបង្ហាត់ <br>បង្រៀនតាមគោលការណ៍ ។</p>
            </button>
        </div>
    </div>
</section>

{{-- Features --}}
<section class="features">
    <h2>អត្ថប្រយោជន៍សំខាន់ៗនៃការសិក្សាកូដ</h2>

    <div class="feature-list">
        <div class="card">
            <img src="{{ asset('assets/images/nlp.png') }}" alt="nlp">
            <h3>ភាសានៃអនាគត</h3>
            <p>Coding គឺជាមូលដ្ឋាននៃបច្ចេកវិទ្យា ទាំង AI, <br>Blockchain, Cybersecurity។</p>
        </div>

        <div class="card">
            <img src="{{ asset('assets/images/programming.png') }}" alt="programming">
            <h3>បង្កើនឱកាសការងារ</h3>
            <p>ការយល់ដឹង Coding អាចជួយបង្កើន តម្លៃ និង <br>ការរីកចម្រើនក្នុងអាជីព ។</p>
        </div>

        <div class="card">
            <img src="{{ asset('assets/images/salary.png') }}" alt="salary">
            <h3>បង្កើតប្រាក់ចំណូល</h3>
            <p>ជំនាញ Coding អាចនាំឲ្យមានប្រាក់ខែ <br>ខ្ពស់ជាងមធ្យម និងឱកាសបង្កើត Startup ផ្ទាល់ខ្លួន</p>
        </div>
    </div>
</section>

 <section class="course-section">

        <h2 class="section-title">ភាសាកូដដែលពេញនិយម</h2>

        <!-- Programming Icons -->
        <div class="language-list">
            <div class="lang"><img src="{{ asset('assets/images/C_Programming_Language.svg.png') }}" alt="C Programming"><span>ភាសា C</span></div>
            <div class="lang"><img src="{{ asset('assets/images/C++_Programming.png') }}" alt="C++ Programming"><span>ភាសា C++</span></div>
            <div class="lang"><img src="{{ asset('assets/images/python_logo_icon_168886.png') }}" alt="Python Programming"><span>ភាសា Python</span></div>
            <div class="lang"><img src="{{ asset('assets/images/Java.png') }}" alt="Java Programming"><span>ភាសា Java</span></div>
            <div class="lang"><img src="{{ asset('assets/images/HTML.png') }}" alt="HTML Programming"><span>ភាសា HTML</span></div>
            <div class="lang"><img src="{{ asset('assets/images/CSS3_logo.svg.png') }}" alt="CSS Programming"><span>ភាសា CSS</span></div>
        </div>

        <!-- Content -->
        <div class="course-content">

            <!-- Left -->
            <div class="left-box">
                <img src="{{ asset('assets/images/People.jpg') }}" alt="Student">
            </div>

            <!-- Divider -->
            <div class="divider"></div>

            <!-- Right -->
            <div class="right-box">
                <img src="{{ asset('assets/images/Full-Stack-Developer.png') }}" alt="Programmer">
            </div>

        </div>

    </section>

    <!-- End Features -->

    <!-- Objective -->
    <section class="benefit-section">

        <h2 class="benefit-title">គោលបំណងនៃការបង្កើតវេបសាយនេះ</h2>

        <!-- Top cards -->
        <div class="benefit-cards">
            <div class="benefit-card">
                <div class="icon"> 
                    <img src="{{ asset('assets/images/skill.png') }}" alt="skill">
                </div>
                <h3>ការអភិវឌ្ឍជំនាញកូដ</h3>
                <p>
                    យើងបង្កើតវេបសាយនេះដើម្បីជួយអ្នករៀនបង្កើតកម្មវិធីនិងកូដផ្ទាល់ខ្លួន
ឲ្យមានការយល់ដឹងនិងជំនាញជាមួយភាសាកូដ
ដែលមានប្រយោជន៍ ។
                </p>
            </div>

            <div class="benefit-card">
                <div class="icon">
                    <img src="{{ asset('assets/images/community.png') }}" alt="community">
                </div>
                <h3>ការបង្កើតសហគមន៍អ្នកសិក្សា</h3>
                <p>
                    យើងចង់បង្កើតសហគមន៍មួយសម្រាប់អ្នកសិក្សាដើម្បីអាចចែករំលែកគ្នា
នូវបទពិសោធន៍និងបច្ចេកវិទ្យា ក្នុងការអភិវឌ្ឍន៍វេបសាយ។
                </p>
            </div>

            <div class="benefit-card">
                <div class="icon">
                    <img src="{{ asset('assets/images/working.png') }}" alt="working">
                </div>
                <h3>ការលើកស្ទួយកម្រិតជំនាញ</h3>
                <p>
                    វេបសាយនេះក៏មានគោលបំណងលើកស្ទួយកម្រិតជំនាញកូដរបស់អ្នក
ដោយផ្តល់ឱកាសរៀនពីគម្រោងជាក់ស្តែងនិងវគ្គសិក្សាថ្មីៗ។
                </p>
            </div>
        </div>

        <!-- Decorative lines -->
        <div class="lines">
            <span class="line l1"></span>
            <span class="line l2"></span>
            <span class="line l3"></span>
            <span class="line l4"></span>
        </div>

        <!-- Course title -->
        <h2 class="course-title">មេរៀនពេញនិយម</h2>

        <!-- Bottom cards -->
        <div class="course-cards">
            <div class="course-card">
                <h3>🌐 HTML ដំបូង</h3>
                <p>
                    រៀនសរសេរគេហទំព័រដំបូងរបស់អ្នក។ ស្វែងយល់ពី tags, 
elements និង structure។
                </p>
            </div>

            <div class="course-card">
                <h3>🎨 CSS Styling</h3>
                <p>
                    ធ្វើឱ្យគេហទំព័ររបស់អ្នកស្អាតតាមរយៈ colors, fonts 
និង layouts។
                </p>
            </div>

            <div class="course-card">
                <h3>⚡ JavaScript Basics</h3>
                <p>
                    បន្ថែម interactivity ទៅគេហទំព័ររបស់អ្នក។ រៀនអំពី 
variables, functions និង events។
                </p>
            </div>
        </div>

    </section>

    <!-- End objective -->

    <!-- Tools -->
    <section class="tools-section">

        <h2 class="tools-title">ឧបករណ៍ដែលយើងប្រើសម្រាប់រៀន</h2>

        <div class="tools-grid">

            <!-- Left -->
            <div class="tool-card">
                <img src="{{ asset('assets/images/VS.png') }}" alt="VS Code">
                <div>
                    <h3>Visual Studio Code</h3>
                    <p>កម្មវិធីសរសេរកូដស្រាល ងាយប្រើ និងគាំទ្រភាសាច្រើន</p>
                </div>
            </div>

            <div class="tool-card">
                <img src="{{ asset('assets/images/IntelliJ_IDEA_Icon.svg.png') }}" alt="IntelliJ">
                <div>
                    <h3>IntelliJ IDEA</h3>
                    <p>IDE សម្រាប់ Java, Kotlin និងភាសាផ្សេងៗ</p>
                </div>
            </div>

            <div class="tool-card">
                <img src="{{ asset('assets/images/GitHub.png') }}" alt="GitHub">
                <div>
                    <h3>GitHub</h3>
                    <p>Platform សម្រាប់ version control និង collaboration</p>
                </div>
            </div>

            <div class="tool-card">
                <img src="{{ asset('assets/images/logo-vercel.png') }}" alt="Vercel">
                <div>
                    <h3>Vercel</h3>
                    <p>សម្រាប់ deploy និង scale web applications</p>
                </div>
            </div>

            <!-- Right -->
            <div class="tool-card">
                <img src="{{ asset('assets/images/Visual_Studio_Icon_2022.svg.png') }}" alt="Visual Studio">
                <div>
                    <h3>Visual Studio 2022</h3>
                    <p>IDE សម្រាប់ .NET, C# និង Desktop Apps</p>
                </div>
            </div>

            <div class="tool-card">
                <img src="{{ asset('assets/images/dev-c--logo-icon-32.png') }}" alt="Dev C++">
                <div>
                    <h3>Dev C++</h3>
                    <p>IDE សម្រាប់ C/C++ ងាយស្រួលសម្រាប់អ្នកចាប់ផ្តើម</p>
                </div>
            </div>

            <div class="tool-card">
                <img src="{{ asset('assets/images/git-icon-logo.png') }}" alt="Git">
                <div>
                    <h3>Git</h3>
                    <p>គ្រប់គ្រង version និងការផ្លាស់ប្តូរកូដ</p>
                </div>
            </div>

            <div class="tool-card">
                <img src="{{ asset('assets/images/namecheap-logo-svg-vector.png') }}" alt="Namecheap">
                <div>
                    <h3>Namecheap</h3>
                    <p>ទិញ domain និង hosting សម្រាប់ website</p>
                </div>
            </div>

        </div>

    </section>

    <!-- Support -->
    <section class="social-section">
        <div class="social-left">
            <h2>គាំទ្រតាមបណ្តាញសង្គម:</h2>

            <a href="https://www.facebook.com/profile.php?id=61586520855760" target="_blank">
                <div class="social-card facebook">
                <h3>Facebook Page</h3>
                <p>Follow on facebook to get latest update</p>
                </div>
            </a>

            <a href="https://t.me/SalaCode007" target="_blank">
                <div class="social-card telegram">
                <h3>Telegram Channel</h3>
                <p>Follow on telegram to get latest update</p>
                </div>
            </a>
        </div>

        <div class="social-right">
            <img src="{{ asset('assets/images/SalaCode-Logo.png') }}" alt="Kids Coding">
        </div>
    </section>

    <section class="cta-wrapper">
        <div class="cta-box">
            <h2>Ready to Get Drip?</h2>
            <p>
                Join our platform today and start learning, practicing,
                and debugging code like a pro.
            </p>
            <button class="cta-btn">Sign Up Now!</button>
        </div>
    </section>
    <!-- End Support -->

{{-- បងបន្ត copy section ផ្សេងៗពី index.html មកទីនេះ --}}
{{-- តែ image path ត្រូវប្តូរពី images/... ទៅ {{ asset('assets/images/...') }} --}}

@endsection

@push('scripts')
<script src="{{ asset('assets/js/home.js') }}"></script>
@endpush