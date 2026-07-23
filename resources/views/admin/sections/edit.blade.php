@extends('layouts.admin')

@section('title', 'Edit Section')
@section('page-title', 'Edit Section')
@section('breadcrumb', 'Edit Section')

@section('content')
    @include('admin.sections.partials.form', [
        'section' => $section,
        'action' => route('admin.sections.update', $section),
        'method' => 'PUT',
        'buttonText' => 'Update Section',
    ])
@endsection
