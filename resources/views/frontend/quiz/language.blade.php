@extends('layouts.frontend')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/lms-quiz.css') }}">
@endpush

@section('content')
    <main class="quiz-shell">
        <div class="quiz-wrap">
            <section class="quiz-hero">
                <div>
                    <p class="quiz-muted"><a href="{{ route('test') }}">កម្រងសំណួរអំពីការសរសេរកម្មវិធី</a> / {{ $language->name }}</p>
                    <h1>តេស្ត {{ $language->name }}</h1>
                    <p>{{ $language->description }}</p>
                </div>
                <a class="quiz-btn secondary" href="{{ route('test') }}">ត្រឡប់ទៅតេស្ត</a>
            </section>

            <form class="quiz-filters" method="GET" action="{{ route('quiz.course', $language) }}">
                <input type="search" name="search" value="{{ request('search') }}" placeholder="ស្វែងរកតេស្ត">
                <select name="difficulty">
                    <option value="">គ្រប់កម្រិតលំបាក</option>
                    @foreach(['Easy', 'Medium', 'Hard'] as $difficulty)
                        <option value="{{ $difficulty }}" @selected(request('difficulty') === $difficulty)>{{ ['Easy' => 'ងាយ', 'Medium' => 'មធ្យម', 'Hard' => 'ពិបាក'][$difficulty] }}</option>
                    @endforeach
                </select>
                <button class="quiz-btn" type="submit">ច្រោះ</button>
            </form>

            @forelse($language->categories as $category)
                <section class="quiz-section">
                    <div class="quiz-section-header">
                        <h2>{{ $category->title }}</h2>
                        <p class="quiz-muted">{{ $category->description }}</p>
                    </div>
                    <div class="quiz-list">
                        @forelse($category->quizzes as $quiz)
                            <div class="quiz-list-item">
                                <div>
                                    <h3>{{ $quiz->title }}</h3>
                                    <p class="quiz-muted">{{ $quiz->questions_count }} សំណួរ / {{ $quiz->estimated_time }} នាទី / ពិន្ទុជាប់ {{ $quiz->passing_score }}%</p>
                                    <span class="quiz-badge {{ strtolower($quiz->difficulty) }}">{{ ['Easy' => 'ងាយ', 'Medium' => 'មធ្យម', 'Hard' => 'ពិបាក'][$quiz->difficulty] ?? $quiz->difficulty }}</span>
                                </div>
                                <form method="POST" action="{{ route('quiz.start', $quiz) }}">
                                    @csrf
                                    <button class="quiz-btn" type="submit">ចាប់ផ្តើមតេស្ត</button>
                                </form>
                            </div>
                        @empty
                            <div class="quiz-list-item">
                                <p class="quiz-muted">មិនទាន់មានតេស្តដែលបានផ្សព្វផ្សាយក្នុងប្រភេទនេះទេ។</p>
                            </div>
                        @endforelse
                    </div>
                </section>
            @empty
                <div class="quiz-card">
                    <h2>មិនមានប្រភេទតេស្តទេ។</h2>
                </div>
            @endforelse
        </div>
    </main>
@endsection
