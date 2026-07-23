@extends('layouts.admin')

@section('title', 'Add Course')
@section('page-title', 'Add Course')
@section('breadcrumb', 'Add Course')

@section('content')
    @include('admin.courses.partials.form', [
        'course' => null,
        'action' => route('admin.courses.store'),
        'method' => 'POST',
        'buttonText' => 'Save Course',
    ])
@endsection
