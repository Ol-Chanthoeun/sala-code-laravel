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
            ក្រុម SALA CODE របស់យើងសូមស្វាគមន៍បងប្អូនមកកាន់វគ្គសិក្សាដ៏ពេញនិយមជាជាច្រើនពេលបច្ចុប្បន្ន
            សូមធ្វើការជ្រើសរើសវគ្គសិក្សាដូចខាងក្រោម។
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

            @forelse($courses as $course)

                <article class="card" data-title="{{ $course->title }}">

                    <div class="card-img">

                        @if($course->thumbnail || $course->image)
                            <img src="{{ asset('uploads/courses/' . ($course->thumbnail ?? $course->image)) }}" alt="{{ $course->title }}">
                        @else
                            <img src="{{ asset('assets/images/c-programming.png') }}" alt="{{ $course->title }}">
                        @endif

                        <div class="card-badge">{{ $course->difficulty_level ?? $course->price }}</div>

                    </div>

                    <div class="card-body">

                        <h3>{{ $course->title }}</h3>

                        <p>{{ $course->short_description ?? $course->description }}</p>

                        <p style="font-size:14px;color:#64748b;margin-top:8px;">
                            {{ $course->programming_language }} | {{ $course->lessons_count }} lessons
                        </p>

                        <a class="btn" href="{{ route('courses.show', $course->slug) }}">
                            ចាប់ផ្តើមរៀន
                        </a>

                    </div>

                </article>

            @empty

                <h2>មិនទាន់មាន Course ទេ</h2>

            @endforelse

        </section>

        <p id="noResult">
            សូមអធ្យាស្រ័យផង course នេះ មិនទាន់មានទេ ខ្ញុំនឹងព្យាយាមបន្ថែមវាពេលក្រោយ...
        </p>
    </main>

@endsection

@push('scripts')
    <script src="{{ asset('assets/js/course.js') }}"></script>
@endpush

