@extends('layouts.admin')

@section('title', 'Add Code Example')
@section('page-title', 'Add Code Example')
@section('breadcrumb', 'Add Code Example')

@section('content')
    @include('admin.examples.partials.form', [
        'example' => null,
        'action' => route('admin.examples.store'),
        'method' => 'POST',
        'buttonText' => 'Save Example',
    ])
@endsection
