@extends('layouts.frontend')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/lms-quiz.css') }}">
@endpush

@section('content')
    @php
        $quiz = $attempt->quiz;
        $questions = $quiz->questions;
    @endphp
    <main class="quiz-shell">
        <div class="quiz-wrap">
            <div class="quiz-topbar">
                <p class="quiz-muted">
                    <a href="{{ route('home') }}">ទំព័រដើម</a> /
                    <a href="{{ route('test') }}">តេស្ត</a> /
                    <a href="{{ route('quiz.course', $quiz->programmingLanguage) }}">{{ $quiz->programmingLanguage?->name }}</a> /
                    {{ $quiz->title }}
                </p>
                <div class="quiz-progress"><div id="quizProgress" class="quiz-progress-fill" style="width:0;"></div></div>
                <p>
                    <strong id="questionCounter">សំណួរ 1 / {{ $questions->count() }}</strong>
                    <span style="margin-left:16px;">ពេលវេលានៅសល់៖ <strong id="timer">{{ $quiz->estimated_time }}:00</strong></span>
                    <span style="margin-left:16px;">ពិន្ទុបច្ចុប្បន្ន៖ <strong id="currentScore">0</strong></span>
                    <span class="quiz-badge {{ strtolower($quiz->difficulty) }}" style="margin-left:16px;">{{ ['Easy' => 'ងាយ', 'Medium' => 'មធ្យម', 'Hard' => 'ពិបាក'][$quiz->difficulty] ?? $quiz->difficulty }}</span>
                </p>
            </div>

            <form id="quizAttemptForm" method="POST" action="{{ route('quiz.submit', $attempt) }}">
                @csrf
                <input type="hidden" name="time_used" id="timeUsed" value="0">

                <div class="quiz-question-card">
                    @foreach($questions as $question)
                        <section class="quiz-question {{ $loop->first ? 'active' : '' }}" data-index="{{ $loop->index }}" data-correct="{{ $question->correct_choice_id }}">
                            <h2>{{ $question->question }}</h2>
                            @foreach($question->choices as $choice)
                                <label class="quiz-choice">
                                    <input type="radio" name="answers[{{ $question->id }}]" value="{{ $choice->id }}" data-correct="{{ $choice->is_correct ? '1' : '0' }}">
                                    <span>{{ $choice->choice_text }}</span>
                                </label>
                            @endforeach
                        </section>
                    @endforeach

                    <div class="quiz-controls">
                        <button class="quiz-btn secondary" id="prevQuestion" type="button">ត្រឡប់ក្រោយ</button>
                        <button class="quiz-btn" id="nextQuestion" type="button">បន្ទាប់</button>
                        <button class="quiz-btn danger" id="finishQuiz" type="submit" style="display:none;">បញ្ចប់តេស្ត</button>
                    </div>
                </div>
            </form>
        </div>
    </main>
@endsection

@push('scripts')
    <script>
        const questions = Array.from(document.querySelectorAll('.quiz-question'));
        let current = 0;
        let secondsTotal = {{ $quiz->estimated_time * 60 }};
        let secondsLeft = secondsTotal;
        const progress = document.querySelector('#quizProgress');
        const counter = document.querySelector('#questionCounter');
        const timer = document.querySelector('#timer');
        const score = document.querySelector('#currentScore');
        const timeUsed = document.querySelector('#timeUsed');
        const prev = document.querySelector('#prevQuestion');
        const next = document.querySelector('#nextQuestion');
        const finish = document.querySelector('#finishQuiz');

        function renderQuestion() {
            questions.forEach((item, index) => item.classList.toggle('active', index === current));
            counter.textContent = `សំណួរ ${current + 1} / ${questions.length}`;
            progress.style.width = `${((current + 1) / questions.length) * 100}%`;
            prev.style.visibility = current === 0 ? 'hidden' : 'visible';
            next.style.display = current === questions.length - 1 ? 'none' : 'inline-flex';
            finish.style.display = current === questions.length - 1 ? 'inline-flex' : 'none';
            updateCurrentScore();
        }

        function updateCurrentScore() {
            let currentScore = 0;
            questions.forEach((question) => {
                const checked = question.querySelector('input[type="radio"]:checked');
                if (checked && checked.dataset.correct === '1') {
                    currentScore++;
                }
            });
            score.textContent = currentScore;
        }

        function renderTimer() {
            const minutes = Math.floor(secondsLeft / 60).toString().padStart(2, '0');
            const seconds = (secondsLeft % 60).toString().padStart(2, '0');
            timer.textContent = `${minutes}:${seconds}`;
            timeUsed.value = secondsTotal - secondsLeft;

            if (secondsLeft <= 0) {
                document.querySelector('#quizAttemptForm').submit();
            }

            secondsLeft--;
        }

        prev.addEventListener('click', () => {
            current = Math.max(0, current - 1);
            renderQuestion();
        });

        next.addEventListener('click', () => {
            current = Math.min(questions.length - 1, current + 1);
            renderQuestion();
        });

        document.querySelectorAll('input[type="radio"]').forEach((input) => {
            input.addEventListener('change', updateCurrentScore);
        });

        renderQuestion();
        renderTimer();
        setInterval(renderTimer, 1000);
    </script>
@endpush
