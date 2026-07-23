@extends('layouts.admin')

@section('title', 'Edit Quiz')
@section('page-title', 'Edit Quiz')
@section('breadcrumb', 'Quizzes')

@section('content')
    <div class="system-info">
        <div class="section-title">Edit LMS Quiz</div>
        <p>Please use the new dynamic quiz builder.</p>
        <a href="{{ route('admin.quizzes.index') }}" class="action-btn" style="width:220px;margin-top:20px;">Open Quizzes</a>
    </div>
@endsection
