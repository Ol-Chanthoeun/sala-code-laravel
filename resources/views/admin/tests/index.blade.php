@extends('layouts.admin')

@section('title', 'Quizzes')
@section('page-title', 'Quizzes')
@section('breadcrumb', 'Quizzes')

@section('content')
    <div class="system-info">
        <div class="section-title">LMS Quiz Module</div>
        <p>The legacy test screen has been replaced by the dynamic LMS quiz system.</p>
        <a href="{{ route('admin.quizzes.index') }}" class="action-btn" style="width:220px;margin-top:20px;">Open Quizzes</a>
    </div>
@endsection
