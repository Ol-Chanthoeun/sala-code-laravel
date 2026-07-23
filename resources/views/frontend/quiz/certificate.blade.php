@extends('layouts.frontend')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/lms-quiz.css') }}">
@endpush

@section('content')
    <main class="quiz-shell">
        <div class="quiz-wrap">
            <section class="quiz-card" style="text-align:center;padding:44px;">
                <p class="quiz-muted">វិញ្ញាបនបត្រ Sala Code</p>
                <h1>វិញ្ញាបនបត្របញ្ជាក់សមិទ្ធផល</h1>
                <p style="font-size:18px;margin-top:18px;">សូមបញ្ជាក់ថា</p>
                <h2 style="font-size:32px;margin-top:10px;">{{ $attempt->user?->name ?? 'អ្នកសិក្សាជាភ្ញៀវ' }}</h2>
                <p style="font-size:18px;margin-top:18px;">បានបញ្ចប់ដោយជោគជ័យ</p>
                <h2>{{ $attempt->quiz?->programmingLanguage?->name }} / {{ $attempt->quiz?->title }}</h2>
                <div class="result-grid" style="margin-top:28px;">
                    <div class="quiz-stat"><strong>{{ $percentage }}%</strong><span>ពិន្ទុចុងក្រោយ</span></div>
                    <div class="quiz-stat"><strong>{{ $attempt->completed_at?->format('Y-m-d') }}</strong><span>បានបញ្ចប់</span></div>
                </div>
                <button class="quiz-btn" onclick="window.print()">បោះពុម្ពវិញ្ញាបនបត្រ</button>
            </section>
        </div>
    </main>
@endsection
