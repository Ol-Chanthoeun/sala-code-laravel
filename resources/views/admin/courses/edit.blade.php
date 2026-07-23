@extends('layouts.admin')

@section('title', 'Edit Course')
@section('page-title', 'Edit Course')
@section('breadcrumb', 'Edit Course')

@section('content')
    @include('admin.courses.partials.form', [
        'course' => $course,
        'action' => route('admin.courses.update', $course),
        'method' => 'PUT',
        'buttonText' => 'Update Course',
    ])
@endsection
