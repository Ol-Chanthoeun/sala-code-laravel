@extends('layouts.admin')

@section('title', 'Add Lesson')
@section('page-title', 'Add Lesson')
@section('breadcrumb', 'Add Lesson')

@section('content')
    @include('admin.lessons.partials.form', [
        'lesson' => null,
        'action' => route('admin.lessons.store'),
        'method' => 'POST',
        'buttonText' => 'Save Lesson',
    ])
@endsection
