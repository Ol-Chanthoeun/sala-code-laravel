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

        @if(auth()->user()->isSuperAdmin())
            <form action="{{ route('admin.users.update-role', $managedUser) }}" method="POST" style="margin-top:25px;border-top:1px solid #eef2f6;padding-top:20px;">
                @csrf
                @method('PATCH')

                <label>Role</label><br>
                <select name="role" required style="width:100%;padding:12px;margin-top:8px;">
                    <option value="user" @selected($managedUser->role === 'user')>User</option>
                    <option value="admin" @selected($managedUser->role === 'admin')>Admin</option>
                    <option value="super_admin" @selected($managedUser->role === 'super_admin')>Super Admin</option>
                </select>

                @if(auth()->id() === $managedUser->id && $managedUser->isSuperAdmin())
                    <label style="display:block;margin-top:12px;">
                        <input type="checkbox" name="confirm_self_role_change" value="1">
                        I understand this may remove my own Super Admin access.
                    </label>
                @endif

                <button type="submit" class="action-btn" style="margin-top:20px;border:none;cursor:pointer;" onclick="return confirm('Change this user role?')">
                    Change Role
                </button>
            </form>
        @endif
    </div>
@endsection
