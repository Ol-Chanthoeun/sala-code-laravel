@extends('layouts.admin')

@section('title', 'Create Admin')
@section('page-title', 'Create Admin')
@section('breadcrumb', 'Create Admin')

@section('content')
    @if($errors->any())
        <p style="color:#dc2626;margin-bottom:15px;">{{ $errors->first() }}</p>
    @endif

    <div class="system-info">
        <div class="section-title">Create New Admin</div>

        <form action="{{ route('admin.admins.store') }}" method="POST">
            @csrf

            <p>
                <label>Full name</label><br>
                <input type="text" name="name" value="{{ old('name') }}" required style="width:100%;padding:12px;margin-top:8px;">
            </p>

            <p style="margin-top:15px;">
                <label>Email</label><br>
                <input type="email" name="email" value="{{ old('email') }}" required style="width:100%;padding:12px;margin-top:8px;">
            </p>

            <p style="margin-top:15px;">
                <label>Password</label><br>
                <input type="password" name="password" required style="width:100%;padding:12px;margin-top:8px;">
            </p>

            <p style="margin-top:15px;">
                <label>Confirm password</label><br>
                <input type="password" name="password_confirmation" required style="width:100%;padding:12px;margin-top:8px;">
            </p>

            <p style="margin-top:15px;">
                <label>Role</label><br>
                <select name="role" required style="width:100%;padding:12px;margin-top:8px;">
                    <option value="admin" selected>Admin</option>
                </select>
            </p>

            <p style="margin-top:15px;">
                <label>Account status</label><br>
                <select name="status" required style="width:100%;padding:12px;margin-top:8px;">
                    <option value="active" selected>Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </p>

            <button type="submit" class="action-btn" style="margin-top:20px;border:none;cursor:pointer;">
                Create Admin
            </button>
        </form>
    </div>
@endsection
