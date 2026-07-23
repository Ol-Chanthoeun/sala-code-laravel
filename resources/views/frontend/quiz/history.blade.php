@extends('layouts.frontend')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/lms-quiz.css') }}">
@endpush

@section('content')
    <main class="quiz-shell">
        <div class="quiz-wrap">
            <section class="quiz-hero">
                <div>
                    <p class="quiz-muted"><a href="{{ route('test') }}">កម្រងសំណួរអំពីការសរសេរកម្មវិធី</a> / ប្រវត្តិ</p>
                    <h1>ប្រវត្តិធ្វើតេស្ត</h1>
                </div>
            </section>

            <section class="quiz-grid">
                @forelse($attempts as $attempt)
                    @php($percentage = $attempt->total_points > 0 ? round(($attempt->score / $attempt->total_points) * 100) : 0)
                    <article class="quiz-card">
                        <p class="quiz-muted">{{ $attempt->quiz?->programmingLanguage?->name }} / {{ $attempt->quiz?->category?->title }}</p>
                        <h2>{{ $attempt->quiz?->title }}</h2>
                        <div class="quiz-stats">
                            <div class="quiz-stat"><strong>{{ $attempt->score }} / {{ $attempt->total_points }}</strong><span>ពិន្ទុ</span></div>
                            <div class="quiz-stat"><strong>{{ $percentage }}%</strong><span>ភាគរយ</span></div>
                        </div>
                        <a class="quiz-btn" href="{{ route('quiz.result', $attempt) }}">មើលលទ្ធផល</a>
                    </article>
                @empty
                    <div class="quiz-card"><h2>មិនទាន់មានប្រវត្តិធ្វើតេស្តទេ។</h2></div>
                @endforelse
            </section>

            <div style="margin-top:20px;">{{ $attempts->links() }}</div>
        </div>
    </main>
@endsection
