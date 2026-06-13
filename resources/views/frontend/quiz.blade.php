@extends('layouts.frontend')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/video.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/quiz.css') }}">
@endpush

@section('content')

<div class="app">
    <h1>Simple Quiz in C Programming</h1>

    <div class="quiz">
        <h2 id="question">
            សំណួរទី ១ តើការប្រព្រឹត្តនៃការបង្ហាញលទ្ធផលនៅក្នុង C គឺ?
        </h2>

        <div id="answer-buttons">
            <button class="btn">1. printf("Hello World")</button>
            <button class="btn">2. echo "Hello World"</button>
            <button class="btn">3. cout("Hello World")</button>
            <button class="btn">4. display("Hello World")</button>
        </div>

        <button id="next-btn">Next Question</button>
    </div>
</div>

@endsection

@push('scripts')
<script src="{{ asset('assets/js/Quiz.js') }}"></script>
@endpush