@extends('layouts.frontend')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/video.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/test.css') }}">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

@endpush

@section('content')

<section class="hero">
    <h1 class="hero-title">
        ជម្រើស<span>ការធ្វើតេស្ត</span>របស់អ្នក
    </h1>

    <p class="hero-sub">
        សូមជ្រើសរើសថ្នាក់ដែលអ្នកចង់ធ្វើតេស្ត
        ដើម្បីវាយតម្លៃជំនាញរបស់អ្នក និងទទួលបានមតិយោបល់ដើម្បីកែលម្អជំនាញរបស់អ្នក!
    </p>
</section>

<main class="content">
    <section class="cards">

        @php
            $tests = [
                ['name' => 'ថ្នាក់ C', 'image' => 'c-programming.png', 'link' => route('quiz')],
                ['name' => 'ថ្នាក់ C++', 'image' => 'cpp.png', 'link' => '#'],
                ['name' => 'ថ្នាក់ Python', 'image' => 'PythonCourse.png', 'link' => '#'],
                ['name' => 'ថ្នាក់ Java', 'image' => 'JavaCourse.jpg', 'link' => '#'],
                ['name' => 'ថ្នាក់ HTML', 'image' => 'HTML-Course.jpg', 'link' => '#'],
                ['name' => 'ថ្នាក់ CSS', 'image' => 'CSS-Course.png', 'link' => '#'],
                ['name' => 'ថ្នាក់ JavaScript', 'image' => 'JavaScript-Course.png', 'link' => '#'],
                ['name' => 'ថ្នាក់ Git', 'image' => 'Git-Course.png', 'link' => '#'],
                ['name' => 'ថ្នាក់ GitHub', 'image' => 'GitHub-Course.jpg', 'link' => '#'],
            ];
        @endphp

        @foreach ($tests as $test)
            <article class="card">
                <div class="card-img">
                    <img src="{{ asset('assets/images/' . $test['image']) }}" alt="{{ $test['name'] }}">
                </div>

                <div class="card-body">
                    <h3>{{ $test['name'] }}</h3>
                    <a class="btn" href="{{ $test['link'] }}">ចាប់ផ្តើមតេស្ត</a>
                </div>
            </article>
        @endforeach

    </section>
</main>

@endsection

@push('scripts')
<script src="{{ asset('assets/js/test.js') }}"></script>
@endpush