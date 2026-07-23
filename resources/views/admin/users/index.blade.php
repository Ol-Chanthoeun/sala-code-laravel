@extends('layouts.admin')

@section('title', 'User Management')
@section('page-title', 'User Management')
@section('breadcrumb', 'Users')

@section('content')
    @if(session('success'))
        <p style="color:green;margin-bottom:15px;">{{ session('success') }}</p>
    @endif

    @if($errors->any())
        <p style="color:#dc2626;margin-bottom:15px;">{{ $errors->first() }}</p>
    @endif

    <div class="system-info" style="margin-bottom:20px;">
        <form method="GET" action="{{ route('admin.users.index') }}" style="display:grid;grid-template-columns:2fr 1fr auto;gap:12px;align-items:end;">
            <p>
                <label>Search users</label><br>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Name or email" style="width:100%;padding:12px;margin-top:8px;">
            </p>
            <p>
                <label>Filter role</label><br>
                <select name="role" style="width:100%;padding:12px;margin-top:8px;">
                    <option value="">All roles</option>
                    @foreach($roles as $value => $label)
                        <option value="{{ $value }}" @selected(request('role') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </p>
            <button class="action-btn" style="border:0;cursor:pointer;">Filter</button>
        </form>
    </div>

    <div class="data-table">
        <div class="table-header">
            <h3>Registered Users</h3>
        </div>

        <div class="table-responsive">
            <table class="users-table">
                <thead>
                    <tr>
                        <th>User name</th>
                        <th>Email</th>
                        <th>Current role</th>
                        <th>Account status</th>
                        <th>Registration date</th>
                        <th class="users-actions-heading">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $managedUser)
                        <tr>
                            <td>{{ $managedUser->name }}</td>
                            <td>{{ $managedUser->email }}</td>
                            <td>{{ $roles[$managedUser->role] ?? $managedUser->role }}</td>
                            <td>{{ ucfirst($managedUser->status) }}</td>
                            <td>{{ $managedUser->created_at?->format('Y-m-d') }}</td>
                            <td class="users-actions-cell">
                                @php
                                    $currentUser = auth()->user();
                                    $canEditUser = ! ($currentUser->isAdmin() && $managedUser->isSuperAdmin());
                                    $canChangeRoles = $currentUser->isSuperAdmin();
                                @endphp

                                @if($canEditUser || $canChangeRoles)
                                    <div class="user-actions">
                                        <div class="user-actions__row user-actions__row--primary">
                                            @if($canEditUser)
                                                <a class="user-action-btn user-action-btn--neutral" href="{{ route('admin.users.edit', $managedUser) }}">
                                                    Edit
                                                </a>
                                            @endif

                                            @if($canChangeRoles)
                                                <form class="user-role-form" action="{{ route('admin.users.update-role', $managedUser) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')

                                                    <div class="user-role-form__controls">
                                                        <select class="user-role-select" name="role" aria-label="Change role for {{ $managedUser->name }}">
                                                            <option value="user" @selected($managedUser->role === 'user')>User</option>
                                                            <option value="admin" @selected($managedUser->role === 'admin')>Admin</option>
                                                            <option value="super_admin" @selected($managedUser->role === 'super_admin')>Super Admin</option>
                                                        </select>

                                                        <button class="user-action-btn user-action-btn--primary" type="submit" onclick="return confirm('Change this user role?')">
                                                            Update Role
                                                        </button>
                                                    </div>

                                                    @if(auth()->id() === $managedUser->id && $managedUser->isSuperAdmin())
                                                        <label class="user-role-confirm">
                                                            <input type="checkbox" name="confirm_self_role_change" value="1">
                                                            <span>Confirm changing your own Super Admin role</span>
                                                        </label>
                                                    @endif
                                                </form>
                                            @endif
                                        </div>

                                        @if($canEditUser)
                                            <div class="user-actions__row user-actions__row--secondary">
                                                <form class="user-action-form" action="{{ route('admin.users.toggle-status', $managedUser) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button class="user-action-btn user-action-btn--warning" type="submit" onclick="return confirm('Change this account status?')">
                                                        {{ $managedUser->status === 'active' ? 'Deactivate' : 'Activate' }}
                                                    </button>
                                                </form>

                                                <form class="user-action-form" action="{{ route('admin.users.destroy', $managedUser) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="user-action-btn user-action-btn--danger" type="submit" onclick="return confirm('Delete this user?')">
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        @endif
                                    </div>
                                @else
                                    <span class="user-actions-empty">Restricted</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align:center;">No users found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div style="margin-top:20px;">
        {{ $users->links() }}
    </div>
@endsection
