@extends('layouts.frontend')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/video.css') }}">
@endpush

@section('content')

<section class="hero">
    <h1 class="hero-title">
        ជម្រើស<span>វិដេអូ</span>របស់យើង
    </h1>

    <p class="hero-sub">
        ក្រុម SALA CODE របស់យើងសូមស្វាគមន៍បងប្អូនមកកាន់វគ្គសិក្សាដ៏ពេញនិយមជាជាច្រើនពេលបច្ចុប្បន្ន សូមធ្វើការជ្រើសរើសវគ្គសិក្សាដូចខាងក្រោម។
    </p>

    <div class="hero-admin">
        <img class="hero-imgAdmin" src="{{ asset('assets/images/SalaCode-Logo.png') }}" alt="Video Hero">
        <a class="Admin" href="https://t.me/SalaCode007" target="_blank">
            ចូលទៅកាន់ Telegram Channel របស់អ្នកគ្រប់គ្រង
        </a>
    </div>
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

        @php
            $videos = [
                ['title' => 'C', 'name' => 'ថ្នាក់ C', 'image' => 'c-programming.png'],
                ['title' => 'C++', 'name' => 'ថ្នាក់ C++', 'image' => 'cpp.png'],
                ['title' => 'Python', 'name' => 'ថ្នាក់ Python', 'image' => 'PythonCourse.png'],
                ['title' => 'Java', 'name' => 'ថ្នាក់ Java', 'image' => 'JavaCourse.jpg'],
                ['title' => 'HTML', 'name' => 'ថ្នាក់ HTML', 'image' => 'HTML-Course.jpg'],
                ['title' => 'CSS', 'name' => 'ថ្នាក់ CSS', 'image' => 'CSS-Course.png'],
                ['title' => 'JavaScript', 'name' => 'ថ្នាក់ JavaScript', 'image' => 'JavaScript-Course.png'],
                ['title' => 'GitHub', 'name' => 'ថ្នាក់ GitHub', 'image' => 'GitHub-Course.jpg'],
                ['title' => 'Git', 'name' => 'ថ្នាក់ Git', 'image' => 'Git-Course.png'],
            ];
        @endphp

        @foreach ($videos as $video)
            <article class="card" data-title="{{ $video['title'] }}">
                <div class="card-img">
                    <img src="{{ asset('assets/images/' . $video['image']) }}" alt="{{ $video['title'] }} Course">
                    <div class="card-badge">Video</div>
                </div>

                <div class="card-body">
                    <h3>{{ $video['name'] }}</h3>
                    <p>
                        {{ $video['title'] }} គឺជាវគ្គសិក្សាវីដេអូសម្រាប់អ្នកចាប់ផ្តើម និងអ្នកចង់ពង្រឹងជំនាញ Programming។
                    </p>

                    <a class="btn" href="https://t.me/olchanthoeun" target="_blank">
                        ទិញឥឡូវនេះ 10 $
                    </a>
                </div>
            </article>
        @endforeach

    </section>

    <p id="noResult">
        សូមអធ្យាស្រ័យផង video នេះ មិនទាន់មានទេ ខ្ញុំនឹងព្យាយាមបន្ថែមវាពេលក្រោយ...
    </p>
</main>

@endsection

@push('scripts')
<script src="{{ asset('assets/js/video.js') }}"></script>
@endpush