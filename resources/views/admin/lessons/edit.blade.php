@extends('layouts.admin')

@section('title', 'Edit Lesson')
@section('page-title', 'Edit Lesson')
@section('breadcrumb', 'Edit Lesson')

@section('content')
    @include('admin.lessons.partials.form', [
        'lesson' => $lesson,
        'action' => route('admin.lessons.update', $lesson),
        'method' => 'PUT',
        'buttonText' => 'Update Lesson',
    ])
@endsection
