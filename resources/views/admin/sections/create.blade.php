@extends('layouts.admin')

@section('title', 'Add Section')
@section('page-title', 'Add Section')
@section('breadcrumb', 'Add Section')

@section('content')
    @include('admin.sections.partials.form', [
        'section' => null,
        'action' => route('admin.sections.store'),
        'method' => 'POST',
        'buttonText' => 'Save Section',
    ])
@endsection
