@extends('layouts.frontend')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/video.css') }}">
@endpush

@section('content')
<main class="video-watch-page">
    <div class="video-library-container">
        <a class="video-back-link" href="{{ route('videos.playlist', $playlistSlug) }}"><i class="fa-solid fa-arrow-left" aria-hidden="true"></i> Back to {{ $playlistTitle }}</a>

        <div class="video-watch-layout">
            <section class="video-watch-main">
                <div class="video-player">
                    @if($currentVideo->embed_url)
                        <iframe src="{{ $currentVideo->embed_url }}" title="{{ $currentVideo->title }}" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                    @else
                        <div class="video-player__empty"><i class="fa-solid fa-video-slash" aria-hidden="true"></i><span>Video is not available yet.</span></div>
                    @endif
                </div>

                <div class="video-watch-copy">
                    <h1>{{ $currentVideo->title }}</h1>
                    @if($currentVideo->duration)<span class="video-duration"><i class="fa-solid fa-clock" aria-hidden="true"></i> {{ $currentVideo->duration }}</span>@endif
                    @if($currentVideo->description)<p>{{ $currentVideo->description }}</p>@endif
                </div>

                <nav class="video-lesson-navigation" aria-label="Video lesson navigation">
                    @if($previousVideo)
                        <a href="{{ route('videos.watch', [$playlistSlug, $previousVideo->slug]) }}"><i class="fa-solid fa-arrow-left" aria-hidden="true"></i> Previous Lesson</a>
                    @else
                        <span></span>
                    @endif
                    @if($nextVideo)
                        <a href="{{ route('videos.watch', [$playlistSlug, $nextVideo->slug]) }}">Next Lesson <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></a>
                    @endif
                </nav>
            </section>

            <aside class="video-playlist-sidebar">
                <div class="video-playlist-sidebar__header"><i class="fa-solid fa-list" aria-hidden="true"></i><div><strong>{{ $playlistTitle }}</strong><span>{{ $videos->count() }} lessons</span></div></div>
                <div class="video-playlist-sidebar__lessons">
                    @foreach($videos as $video)
                        <a class="video-sidebar-lesson {{ $video->is($currentVideo) ? 'active' : '' }}" href="{{ route('videos.watch', [$playlistSlug, $video->slug]) }}">
                            <span class="video-sidebar-lesson__number">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                            <span><strong>{{ $video->title }}</strong>@if($video->duration)<small><i class="fa-solid fa-clock" aria-hidden="true"></i> {{ $video->duration }}</small>@endif</span>
                            <i class="fa-solid fa-play video-sidebar-lesson__play" aria-hidden="true"></i>
                        </a>
                    @endforeach
                </div>
            </aside>
        </div>
    </div>
</main>
@endsection
