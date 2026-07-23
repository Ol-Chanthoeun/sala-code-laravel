@extends('layouts.admin')

@section('title', 'Admin Management')
@section('page-title', 'Admin Management')
@section('breadcrumb', 'Admin Management')

@section('content')
    <a href="{{ route('admin.admins.create') }}" class="action-btn" style="width:210px;margin-bottom:20px;">
        <i class="fas fa-user-plus"></i> Create Admin
    </a>

    @if(session('success'))
        <p style="color:green;margin-bottom:15px;">{{ session('success') }}</p>
    @endif

    <div class="data-table">
        <div class="table-header">
            <h3>Admins and Super Admins</h3>
        </div>

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Registered</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($admins as $admin)
                        <tr>
                            <td>{{ $admin->name }}</td>
                            <td>{{ $admin->email }}</td>
                            <td>{{ ucfirst(str_replace('_', ' ', $admin->role)) }}</td>
                            <td>{{ ucfirst($admin->status) }}</td>
                            <td>{{ $admin->created_at?->format('Y-m-d') }}</td>
                            <td>
                                @if($admin->isAdmin())
                                    <a href="{{ route('admin.admins.edit', $admin) }}">Edit</a>
                                    <form action="{{ route('admin.admins.destroy', $admin) }}" method="POST" style="display:inline;margin-left:8px;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" onclick="return confirm('Delete this admin account?')">Delete</button>
                                    </form>
                                @else
                                    Protected
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align:center;">No admins found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div style="margin-top:20px;">
        {{ $admins->links() }}
    </div>
@endsection
