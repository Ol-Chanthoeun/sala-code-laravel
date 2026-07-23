@extends('layouts.admin')

@section('title', 'Edit Code Example')
@section('page-title', 'Edit Code Example')
@section('breadcrumb', 'Edit Code Example')

@section('content')
    @include('admin.examples.partials.form', [
        'example' => $example,
        'action' => route('admin.examples.update', $example),
        'method' => 'PUT',
        'buttonText' => 'Update Example',
    ])
@endsection
