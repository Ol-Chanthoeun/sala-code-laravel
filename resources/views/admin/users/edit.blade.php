@extends('layouts.admin')

@section('title', 'Edit User')
@section('page-title', 'Edit User')
@section('breadcrumb', 'Edit User')

@section('content')
    @if($errors->any())
        <p style="color:#dc2626;margin-bottom:15px;">{{ $errors->first() }}</p>
    @endif

    <div class="system-info">
        <div class="section-title">Edit {{ $managedUser->name }}</div>

        <form action="{{ route('admin.users.update', $managedUser) }}" method="POST">
            @csrf
            @method('PUT')

            <p>
                <label>Full name</label><br>
                <input type="text" name="name" value="{{ old('name', $managedUser->name) }}" required style="width:100%;padding:12px;margin-top:8px;">
            </p>

            <p style="margin-top:15px;">
                <label>Email</label><br>
                <input type="email" name="email" value="{{ old('email', $managedUser->email) }}" required style="width:100%;padding:12px;margin-top:8px;">
            </p>

            <p style="margin-top:15px;">
                <label>Account status</label><br>
                <select name="status" required style="width:100%;padding:12px;margin-top:8px;">
                    <option value="active" @selected(old('status', $managedUser->status) === 'active')>Active</option>
                    <option value="inactive" @selected(old('status', $managedUser->status) === 'inactive')>Inactive</option>
                </select>
            </p>

            <button type="submit" class="action-btn" style="margin-top:20px;border:none;cursor:pointer;">
                Save User
            </button>
        </form>

    </div>
@endsection
