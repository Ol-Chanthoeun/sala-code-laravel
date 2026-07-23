@extends('layouts.admin')

@section('title', 'Add Quiz')
@section('page-title', 'Add Quiz')
@section('breadcrumb', 'Quizzes')

@section('content')
    <div class="system-info">
        <div class="section-title">Create LMS Quiz</div>
        <p>Please use the new dynamic quiz builder.</p>
        <a href="{{ route('admin.quizzes.create') }}" class="action-btn" style="width:220px;margin-top:20px;">Create Quiz</a>
    </div>
@endsection
