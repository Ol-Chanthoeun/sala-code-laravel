@extends('layouts.frontend')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/lms-quiz.css') }}">
@endpush

@section('content')
    @php
        $percentage = $attempt->total_points > 0 ? round(($attempt->score / $attempt->total_points) * 100) : 0;
        $badge = $percentage >= 90 ? 'ល្អឥតខ្ចោះ' : ($percentage >= 75 ? 'ល្អណាស់' : ($percentage >= 60 ? 'ជាប់' : 'សូមបន្តហ្វឹកហាត់'));
    @endphp
    <main class="quiz-shell">
        <div class="quiz-wrap">
            <section class="quiz-card">
                <p class="quiz-muted">{{ $attempt->quiz?->programmingLanguage?->name }} / {{ $attempt->quiz?->title }}</p>
                <h1>អបអរសាទរ!</h1>
                <p class="quiz-muted">លទ្ធផលតេស្តរបស់អ្នករួចរាល់ហើយ។</p>

                <div class="result-grid">
                    <div class="quiz-stat"><strong>{{ $attempt->score }} / {{ $attempt->total_points }}</strong><span>ពិន្ទុ</span></div>
                    <div class="quiz-stat"><strong>{{ $attempt->correct_count }}</strong><span>ចម្លើយត្រឹមត្រូវ</span></div>
                    <div class="quiz-stat"><strong>{{ $attempt->incorrect_count }}</strong><span>ចម្លើយមិនត្រឹមត្រូវ</span></div>
                    <div class="quiz-stat"><strong>{{ $percentage }}%</strong><span>ភាគរយ</span></div>
                    <div class="quiz-stat"><strong>{{ ceil($attempt->time_used / 60) }} នាទី</strong><span>ពេលវេលាបានប្រើ</span></div>
                    <div class="quiz-stat"><strong>{{ $badge }}</strong><span>កម្រិតសមិទ្ធផល</span></div>
                </div>

                <div style="display:flex;gap:10px;flex-wrap:wrap;">
                    <a class="quiz-btn" href="{{ route('quiz.review', $attempt) }}">ពិនិត្យចម្លើយឡើងវិញ</a>
                    <form method="POST" action="{{ route('quiz.start', $attempt->quiz) }}">@csrf<button class="quiz-btn secondary" type="submit">ធ្វើតេស្តម្ដងទៀត</button></form>
                    <a class="quiz-btn secondary" href="{{ route('quiz.course', $attempt->quiz->programmingLanguage) }}">ត្រឡប់ទៅមេរៀន</a>
                    <a class="quiz-btn secondary" href="{{ route('quiz.leaderboard', $attempt->quiz) }}">តារាងចំណាត់ថ្នាក់</a>
                    @if($percentage >= $attempt->quiz->passing_score)
                        <a class="quiz-btn secondary" href="{{ route('quiz.certificate', $attempt) }}">វិញ្ញាបនបត្រ</a>
                    @endif
                </div>
            </section>
        </div>
    </main>
@endsection
