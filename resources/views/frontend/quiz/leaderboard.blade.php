@extends('layouts.frontend')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/lms-quiz.css') }}">
@endpush

@section('content')
    <main class="quiz-shell">
        <div class="quiz-wrap">
            <section class="quiz-card">
                <p class="quiz-muted">{{ $quiz->programmingLanguage?->name }} / {{ $quiz->category?->title }}</p>
                <h1>តារាងចំណាត់ថ្នាក់ {{ $quiz->title }}</h1>
                <div class="quiz-list" style="margin-top:18px;">
                    @forelse($attempts as $attempt)
                        <div class="quiz-list-item">
                            <div>
                                <strong>#{{ $loop->iteration }} {{ $attempt->user?->name ?? 'អ្នកសិក្សាជាភ្ញៀវ' }}</strong>
                                <p class="quiz-muted">{{ $attempt->score }} / {{ $attempt->total_points }} ពិន្ទុ / {{ ceil($attempt->time_used / 60) }} នាទី</p>
                            </div>
                        </div>
                    @empty
                        <p class="quiz-muted">មិនទាន់មានទិន្នន័យក្នុងតារាងចំណាត់ថ្នាក់ទេ។</p>
                    @endforelse
                </div>
            </section>
        </div>
    </main>
@endsection
