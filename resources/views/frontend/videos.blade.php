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
        ក្រុម SALA CODE របស់យើងសូមស្វាគមន៍បងប្អូនមកកាន់វគ្គសិក្សាដ៏ពេញនិយមជាច្រើន។
        សូមជ្រើសរើសវីដេអូសិក្សាខាងក្រោម។
    </p>

    <div class="hero-admin">
        <img class="hero-imgAdmin"
            src="{{ asset('assets/images/SalaCode-Logo.png') }}"
            alt="Video Hero">

        <a class="Admin"
            href="https://t.me/SalaCode007"
            target="_blank">
            ចូលទៅកាន់ Telegram Channel របស់អ្នកគ្រប់គ្រង
        </a>
    </div>
</section>

<main class="content">

    <div class="search-wrap">
        <div class="search">
            <span class="search-ic">🔍</span>

            <input
                id="searchInput"
                type="text"
                placeholder="ស្វែងរកវីដេអូ..."
            >

            <button id="searchBtn" type="button">
                ស្វែងរក
            </button>
        </div>
    </div>

    <section class="cards">

        @forelse($videos as $video)

            <article class="card"
                data-title="{{ strtolower($video->title) }}">

                <div class="card-img">

                    @if($video->thumbnail)
                        <img
                            src="{{ asset('uploads/videos/' . $video->thumbnail) }}"
                            alt="{{ $video->title }}">
                    @else
                        <img
                            src="{{ asset('assets/images/SalaCode-Logo.png') }}"
                            alt="{{ $video->title }}">
                    @endif

                    <div class="card-badge">
                        Video
                    </div>
                </div>

                <div class="card-body">

                    <h3>{{ $video->title }}</h3>

                    <p>
                        {{ $video->description }}
                    </p>

                    @if($video->youtube_link)

                        <a class="btn"
                            href="{{ $video->youtube_link }}"
                            target="_blank">
                            មើលវីដេអូ
                        </a>

                    @else

                        <a class="btn" href="#">
                            មិនទាន់មាន Link
                        </a>

                    @endif

                </div>

            </article>

        @empty

            <div style="width:100%;text-align:center;padding:50px;">
                <h2>មិនទាន់មានវីដេអូទេ</h2>
            </div>

        @endforelse

    </section>

    <p id="noResult">
        សូមអធ្យាស្រ័យផង Video នេះ មិនទាន់មានទេ...
    </p>

</main>

@endsection

@push('scripts')
<script src="{{ asset('assets/js/video.js') }}"></script>
@endpush