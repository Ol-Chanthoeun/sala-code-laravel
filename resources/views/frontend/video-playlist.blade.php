@extends('layouts.frontend')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/video.css') }}">
@endpush

@section('content')
<main class="video-library-page">
    <div class="video-library-container">
        <a class="video-back-link" href="{{ route('videos') }}"><i class="fa-solid fa-arrow-left" aria-hidden="true"></i> Back to Videos</a>

        <header class="playlist-header">
            <span><i class="fa-solid fa-list" aria-hidden="true"></i> Video Playlist</span>
            <h1>{{ $playlistTitle }}</h1>
            <p>{{ $videos->count() }} Video Lessons</p>
        </header>

        <section class="playlist-lessons" aria-label="{{ $playlistTitle }} video lessons">
            @foreach($videos as $video)
                <article class="playlist-lesson-card">
                    <a class="playlist-lesson-card__thumbnail" href="{{ route('videos.watch', [$playlistSlug, $video->slug]) }}">
                        <img src="{{ $video->display_thumbnail }}" alt="{{ $video->title }}">
                        <span><i class="fa-solid fa-play" aria-hidden="true"></i></span>
                    </a>
                    <div class="playlist-lesson-card__content">
                        <div class="playlist-lesson-card__number">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</div>
                        <div class="playlist-lesson-card__details">
                            <h2>{{ $video->title }}</h2>
                            @if($video->description)<p>{{ Str::limit($video->description, 150) }}</p>@endif
                            @if($video->duration)<span class="video-duration"><i class="fa-solid fa-clock" aria-hidden="true"></i> {{ $video->duration }}</span>@endif
                        </div>
                    </div>
                    <a class="video-watch-button" href="{{ route('videos.watch', [$playlistSlug, $video->slug]) }}"><i class="fa-solid fa-circle-play" aria-hidden="true"></i> Watch</a>
                </article>
            @endforeach
        </section>
    </div>
</main>
@endsection
