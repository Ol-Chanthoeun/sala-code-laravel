@extends('layouts.frontend')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/lms-quiz.css') }}">
@endpush

@section('content')
    <main class="quiz-shell">
        <div class="quiz-wrap">
            <section class="quiz-hero">
                <div>
                    <h1>កម្រងសំណួរអំពីការសរសេរកម្មវិធី</h1>
                    <p>ជ្រើសរើសភាសាសរសេរកម្មវិធី រួចចាប់ផ្តើមធ្វើតេស្តតាមប្រភេទ និងកម្រិតលំបាក។</p>
                </div>
                <a class="quiz-btn secondary" href="{{ route('quiz.history') }}">ប្រវត្តិធ្វើតេស្ត</a>
            </section>

            <form class="quiz-filters" method="GET" action="{{ route('test') }}">
                <input type="search" name="search" value="{{ request('search') }}" placeholder="ស្វែងរកភាសា">
                <select name="difficulty">
                    <option value="">គ្រប់កម្រិតលំបាក</option>
                    @foreach(['Beginner', 'Intermediate', 'Advanced', 'Easy', 'Medium', 'Hard'] as $difficulty)
                        <option value="{{ $difficulty }}" @selected(request('difficulty') === $difficulty)>{{ ['Beginner' => 'កម្រិតដំបូង', 'Intermediate' => 'កម្រិតមធ្យម', 'Advanced' => 'កម្រិតខ្ពស់', 'Easy' => 'ងាយ', 'Medium' => 'មធ្យម', 'Hard' => 'ពិបាក'][$difficulty] }}</option>
                    @endforeach
                </select>
                <button class="quiz-btn" type="submit">ស្វែងរកតេស្ត</button>
            </form>

            @if($continueAttempts->isNotEmpty())
                <section class="quiz-card" style="margin-bottom:20px;">
                    <h2>បន្តធ្វើតេស្ត</h2>
                    @foreach($continueAttempts as $attempt)
                        <p style="margin-top:10px;">
                            <a href="{{ route('quiz.take', $attempt) }}">{{ $attempt->quiz?->programmingLanguage?->name }} / {{ $attempt->quiz?->title }}</a>
                        </p>
                    @endforeach
                </section>
            @endif

            <section class="quiz-grid">
                @forelse($languages as $language)
                    @php
                        $quizCount = $language->quizzes->count();
                        $questionCount = $language->quizzes->sum('questions_count');
                    @endphp
                    <article class="quiz-card">
                        <div class="language-logo">
                            @if($language->logo)
                                <img src="{{ Str::startsWith($language->logo, 'programming-languages/') ? Storage::url($language->logo) : asset('assets/images/' . $language->logo) }}" alt="{{ $language->name }}">
                            @else
                                {{ Str::of($language->name)->substr(0, 2)->upper() }}
                            @endif
                        </div>
                        <h2>{{ $language->name }}</h2>
                        <p class="quiz-muted">{{ Str::limit($language->description, 110) }}</p>

                        <div class="quiz-stats">
                            <div class="quiz-stat"><strong>{{ $quizCount }}</strong><span>តេស្ត</span></div>
                            <div class="quiz-stat"><strong>{{ $questionCount }}</strong><span>សំណួរ</span></div>
                            <div class="quiz-stat"><strong>{{ ['Beginner' => 'កម្រិតដំបូង', 'Intermediate' => 'កម្រិតមធ្យម', 'Advanced' => 'កម្រិតខ្ពស់', 'Easy' => 'ងាយ', 'Medium' => 'មធ្យម', 'Hard' => 'ពិបាក'][$language->difficulty] ?? $language->difficulty }}</strong><span>កម្រិតលំបាក</span></div>
                            <div class="quiz-stat"><strong>{{ $language->estimated_time }} នាទី</strong><span>ពេលវេលាប៉ាន់ស្មាន</span></div>
                        </div>

                        <a class="quiz-btn" href="{{ route('quiz.course', $language) }}">ចាប់ផ្តើមរៀន</a>
                    </article>
                @empty
                    <div class="quiz-card">
                        <h2>មិនមានភាសាសម្រាប់ធ្វើតេស្តទេ។</h2>
                    </div>
                @endforelse
            </section>

            @if($popularQuizzes->isNotEmpty() || $recentAttempts->isNotEmpty())
                <section class="quiz-grid" style="margin-top:22px;">
                    <div class="quiz-card">
                        <h2>តេស្តពេញនិយម</h2>
                        @foreach($popularQuizzes as $quiz)
                            <p style="margin-top:10px;">
                                <a href="{{ route('quiz.leaderboard', $quiz) }}">{{ $quiz->programmingLanguage?->name }} / {{ $quiz->title }}</a>
                            </p>
                        @endforeach
                    </div>
                    <div class="quiz-card">
                        <h2>បានធ្វើថ្មីៗ</h2>
                        @forelse($recentAttempts as $attempt)
                            <p style="margin-top:10px;">
                                <a href="{{ route('quiz.result', $attempt) }}">{{ $attempt->quiz?->programmingLanguage?->name }} / {{ $attempt->quiz?->title }}</a>
                            </p>
                        @empty
                            <p class="quiz-muted">មិនទាន់មានប្រវត្តិធ្វើតេស្តថ្មីៗទេ។</p>
                        @endforelse
                    </div>
                </section>
            @endif
        </div>
    </main>
@endsection
