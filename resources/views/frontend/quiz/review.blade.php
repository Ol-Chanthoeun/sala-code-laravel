@extends('layouts.frontend')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/lms-quiz.css') }}">
@endpush

@section('content')
    <main class="quiz-shell">
        <div class="quiz-wrap">
            <section class="quiz-hero">
                <div>
                    <p class="quiz-muted">{{ $attempt->quiz?->programmingLanguage?->name }} / {{ $attempt->quiz?->title }}</p>
                    <h1>ពិនិត្យចម្លើយឡើងវិញ</h1>
                </div>
                <a class="quiz-btn secondary" href="{{ route('quiz.result', $attempt) }}">ត្រឡប់ទៅលទ្ធផល</a>
            </section>

            @foreach($attempt->quiz->questions as $question)
                @php
                    $answer = $attempt->answers->firstWhere('quiz_question_id', $question->id);
                @endphp
                <article class="review-answer {{ $answer?->is_correct ? 'correct' : 'incorrect' }}">
                    <h3>{{ $loop->iteration }}. {{ $question->question }}</h3>
                    @foreach($question->choices as $choice)
                        <p style="margin-top:8px;">
                            @if($choice->id === $question->correct_choice_id) ✓ @elseif($answer?->quiz_choice_id === $choice->id) ✕ @else • @endif
                            {{ $choice->choice_text }}
                        </p>
                    @endforeach
                    @if($question->explanation)
                        <p class="quiz-muted" style="margin-top:10px;">{{ $question->explanation }}</p>
                    @endif
                </article>
            @endforeach
        </div>
    </main>
@endsection
