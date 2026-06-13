@extends('layouts.frontend')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/course.css') }}">
@endpush

@section('content')

<section class="hero">
    <h1 class="hero-title">
        ជម្រើស<span>វគ្គសិក្សា</span>របស់យើង
    </h1>

    <p class="hero-sub">
        ក្រុម SALA CODE របស់យើងសូមស្វាគមន៍បងប្អូនមកកាន់វគ្គសិក្សាដ៏ពេញនិយមជាជាច្រើនពេលបច្ចុប្បន្ន សូមធ្វើការជ្រើសរើសវគ្គសិក្សាដូចខាងក្រោម។
    </p>
</section>

<main class="content">
    <div class="search-wrap">
        <div class="search">
            <span class="search-ic">🔍</span>
            <input id="searchInput" type="text" placeholder="ស្វែងរកថ្នាក់រៀន..." />
            <button id="searchBtn" type="button">ស្វែងរក</button>
        </div>
    </div>

    <section class="cards">

        <article class="card" data-title="C">
            <div class="card-img">
                <img src="{{ asset('assets/images/c-programming.png') }}" alt="C Programming Course">
                <div class="card-badge">Free</div>
            </div>

            <div class="card-body">
                <h3>ថ្នាក់ C</h3>
                <p>
                    C គឺជាភាសា Programming ដ៏មានឥទ្ធិពលនិងត្រូវបានប្រើប្រាស់យ៉ាងទូលំទូលាយ ។ C ត្រូវបានបង្កើតឡើងដោយលោក Dennis Ritchie នៅឆ្នាំ 1970 នៅ Bell Labs ហើយវាបាន
                    ក្លាយជាឫសគល់សម្រាប់ភាសាកម្មវិធីជាច្រើនទៀតដូចជា C++, Java និង Python ។
                </p>
                <a class="btn" href="{{ route('c_programming') }}">ចាប់ផ្តើមរៀន</a>
            </div>
        </article>

        <article class="card" data-title="C++">
            <div class="card-img">
                <img src="{{ asset('assets/images/cpp.png') }}" alt="C++ Programming Course">
                <div class="card-badge">Free</div>
            </div>

            <div class="card-body">
                <h3>ថ្នាក់ C++</h3>
                <p>
                    C++ គឺជា programming language មួយ ដែលបង្កើតឡើងដោយ Bjarne Stroustrup (1983)។ វាកើតពី C language ប៉ុន្តែបន្ថែមមុខងារ OOP។
                </p>
                <a class="btn" href="#">ចាប់ផ្តើមរៀន</a>
            </div>
        </article>

        <article class="card" data-title="Python">
            <div class="card-img">
                <img src="{{ asset('assets/images/PythonCourse.png') }}" alt="Python Course">
                <div class="card-badge">Free</div>
            </div>

            <div class="card-body">
                <h3>ថ្នាក់ Python</h3>
                <p>
                    Python គឺជា programming language មួយដែល ងាយស្រួលរៀន និងអាន។ វាពេញនិយមសម្រាប់ Web, AI, Data, Automation, Software។
                </p>
                <a class="btn" href="#">ចាប់ផ្តើមរៀន</a>
            </div>
        </article>

        <article class="card" data-title="Java">
            <div class="card-img">
                <img src="{{ asset('assets/images/JavaCourse.jpg') }}" alt="Java Course">
                <div class="card-badge">Free</div>
            </div>

            <div class="card-body">
                <h3>ថ្នាក់ Java</h3>
                <p>
                    Java គឺជា programming language មួយដែល powerful និងប្រើ OOP។ Java អាចដំណើរការលើ platform ច្រើន។
                </p>
                <a class="btn" href="#">ចាប់ផ្តើមរៀន</a>
            </div>
        </article>

        <article class="card" data-title="HTML">
            <div class="card-img">
                <img src="{{ asset('assets/images/HTML-Course.jpg') }}" alt="HTML Course">
                <div class="card-badge">Free</div>
            </div>

            <div class="card-body">
                <h3>ថ្នាក់ HTML</h3>
                <p>
                    HTML គឺជា Markup Language ប្រើសម្រាប់បង្កើតរចនាសម្ព័ន្ធ Web page ដូចជា អត្ថបទ រូបភាព Link និង Button។
                </p>
                <a class="btn" href="#">ចាប់ផ្តើមរៀន</a>
            </div>
        </article>

        <article class="card" data-title="CSS">
            <div class="card-img">
                <img src="{{ asset('assets/images/CSS-Course.png') }}" alt="CSS Course">
                <div class="card-badge">Free</div>
            </div>

            <div class="card-body">
                <h3>ថ្នាក់ CSS</h3>
                <p>
                    CSS គឺជាភាសាសម្រាប់រចនា Web page ឲ្យស្អាត មានពណ៌ Layout និង Responsive។
                </p>
                <a class="btn" href="#">ចាប់ផ្តើមរៀន</a>
            </div>
        </article>

        <article class="card" data-title="JavaScript">
            <div class="card-img">
                <img src="{{ asset('assets/images/JavaScript-Course.png') }}" alt="JavaScript Course">
                <div class="card-badge">Free</div>
            </div>

            <div class="card-body">
                <h3>ថ្នាក់ JavaScript</h3>
                <p>
                    JavaScript គឺជា programming language សម្រាប់ធ្វើឲ្យ Website មានចលនា និង Logic។
                </p>
                <a class="btn" href="#">ចាប់ផ្តើមរៀន</a>
            </div>
        </article>

        <article class="card" data-title="GitHub">
            <div class="card-img">
                <img src="{{ asset('assets/images/GitHub-Course.jpg') }}" alt="GitHub Course">
                <div class="card-badge">Free</div>
            </div>

            <div class="card-body">
                <h3>ថ្នាក់ GitHub</h3>
                <p>
                    GitHub គឺជា website សម្រាប់រក្សាទុក និងគ្រប់គ្រង Source Code ដោយប្រើ Git។
                </p>
                <a class="btn" href="#">ចាប់ផ្តើមរៀន</a>
            </div>
        </article>

        <article class="card" data-title="Git">
            <div class="card-img">
                <img src="{{ asset('assets/images/Git-Course.png') }}" alt="Git Course">
                <div class="card-badge">Free</div>
            </div>

            <div class="card-body">
                <h3>ថ្នាក់ Git</h3>
                <p>
                    Git គឺជា Version Control System សម្រាប់គ្រប់គ្រង និងតាមដានការផ្លាស់ប្ដូរ code។
                </p>
                <a class="btn" href="#">ចាប់ផ្តើមរៀន</a>
            </div>
        </article>

    </section>

    <p id="noResult">
        សូមអធ្យាស្រ័យផង course នេះ មិនទាន់មានទេ ខ្ញុំនឹងព្យាយាមបន្ថែមវាពេលក្រោយ...
    </p>
</main>

@endsection

@push('scripts')
<script src="{{ asset('assets/js/course.js') }}"></script>
@endpush