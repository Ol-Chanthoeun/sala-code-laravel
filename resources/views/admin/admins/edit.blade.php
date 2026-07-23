@extends('layouts.admin')

@section('title', 'Edit Admin')
@section('page-title', 'Edit Admin')
@section('breadcrumb', 'Edit Admin')

@section('content')
    @if($errors->any())
        <p style="color:#dc2626;margin-bottom:15px;">{{ $errors->first() }}</p>
    @endif

    <div class="system-info">
        <div class="section-title">Edit Admin</div>

        <form action="{{ route('admin.admins.update', $admin) }}" method="POST">
            @csrf
            @method('PUT')

            <p>
                <label>Full name</label><br>
                <input type="text" name="name" value="{{ old('name', $admin->name) }}" required style="width:100%;padding:12px;margin-top:8px;">
            </p>

            <p style="margin-top:15px;">
                <label>Email</label><br>
                <input type="email" name="email" value="{{ old('email', $admin->email) }}" required style="width:100%;padding:12px;margin-top:8px;">
            </p>

            <p style="margin-top:15px;">
                <label>New password</label><br>
                <input type="password" name="password" style="width:100%;padding:12px;margin-top:8px;">
            </p>

            <p style="margin-top:15px;">
                <label>Confirm new password</label><br>
                <input type="password" name="password_confirmation" style="width:100%;padding:12px;margin-top:8px;">
            </p>

            <p style="margin-top:15px;">
                <label>Status</label><br>
                <select name="status" required style="width:100%;padding:12px;margin-top:8px;">
                    <option value="active" @selected(old('status', $admin->status) === 'active')>Active</option>
                    <option value="inactive" @selected(old('status', $admin->status) === 'inactive')>Inactive</option>
                </select>
            </p>

            <button type="submit" class="action-btn" style="margin-top:20px;border:none;cursor:pointer;">
                Save Admin
            </button>
        </form>
    </div>
@endsection
