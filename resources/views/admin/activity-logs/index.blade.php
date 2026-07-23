@extends('layouts.admin')

@section('title', 'Activity Logs')
@section('page-title', 'Activity Logs')
@section('breadcrumb', 'Activity Logs')

@push('styles')
<style>
    .audit-toolbar { align-items:end; display:grid; gap:12px; grid-template-columns:2fr repeat(4, minmax(145px, 1fr)) auto; margin-bottom:20px; }
    .audit-toolbar label { color:#374151; display:block; font-size:13px; font-weight:700; margin-bottom:6px; }
    .audit-toolbar input, .audit-toolbar select { border:1px solid #d1d5db; border-radius:7px; padding:10px; width:100%; }
    .audit-actions { display:flex; flex-wrap:wrap; gap:10px; margin:16px 0; }
    .audit-button { align-items:center; background:#4f46e5; border:0; border-radius:7px; color:#fff; cursor:pointer; display:inline-flex; gap:7px; padding:10px 14px; text-decoration:none; }
    .audit-button.secondary { background:#64748b; }
    .audit-button.danger { background:#dc2626; }
    .audit-description { max-width:260px; white-space:normal; }
    .audit-muted { color:#64748b; font-size:12px; }
    @media (max-width:1100px) { .audit-toolbar { grid-template-columns:repeat(2, minmax(0,1fr)); } }
    @media (max-width:640px) { .audit-toolbar { grid-template-columns:1fr; } }
</style>
@endpush

@section('content')
    @if(session('success'))<p style="color:green;margin-bottom:15px;">{{ session('success') }}</p>@endif
    @if($errors->any())<p style="color:#dc2626;margin-bottom:15px;">{{ $errors->first() }}</p>@endif

    <form class="audit-toolbar" method="GET" action="{{ route('admin.activity-logs.index') }}">
        <div><label for="search">Search</label><input id="search" name="search" value="{{ request('search') }}" placeholder="User, description, or IP"></div>
        <div><label for="user_id">User</label><select id="user_id" name="user_id"><option value="">All users</option>@foreach($users as $user)<option value="{{ $user->id }}" @selected((string) request('user_id') === (string) $user->id)>{{ $user->name }}</option>@endforeach</select></div>
        <div><label for="module">Module</label><select id="module" name="module"><option value="">All modules</option>@foreach($modules as $module)<option value="{{ $module }}" @selected(request('module') === $module)>{{ $module }}</option>@endforeach</select></div>
        <div><label for="action">Action</label><select id="action" name="action"><option value="">All actions</option>@foreach($actions as $action)<option value="{{ $action }}" @selected(request('action') === $action)>{{ $action }}</option>@endforeach</select></div>
        <div><label for="date">Date</label><input id="date" type="date" name="date" value="{{ request('date') }}"></div>
        <button class="audit-button" type="submit"><i class="fas fa-filter"></i> Filter</button>
    </form>

    <div class="audit-actions">
        <button class="audit-button danger" type="submit" form="bulkDeleteForm" onclick="return confirm('Delete the selected activity logs?')"><i class="fas fa-trash"></i> Delete Selected</button>
        <a class="audit-button secondary" href="{{ route('admin.activity-logs.index') }}"><i class="fas fa-rotate-left"></i> Reset Filters</a>
        <form action="{{ route('admin.activity-logs.clear') }}" method="POST" style="margin-left:auto;">@csrf @method('DELETE')<button class="audit-button danger" type="submit" onclick="return confirm('Clear all activity logs? This cannot be undone.')"><i class="fas fa-broom"></i> Clear All Logs</button></form>
    </div>

    <form id="bulkDeleteForm" action="{{ route('admin.activity-logs.destroy-selected') }}" method="POST">
        @csrf @method('DELETE')
        <div class="data-table">
            <div class="table-header"><h3>Audit Trail</h3></div>
            <div class="table-responsive">
                <table>
                    <thead><tr><th><input id="selectAllLogs" type="checkbox" aria-label="Select all logs"></th><th>ID</th><th>User</th><th>Role</th><th>Action</th><th>Module</th><th>Description</th><th>IP Address</th><th>Browser</th><th>Date & Time</th><th>Details</th></tr></thead>
                    <tbody>
                    @forelse($logs as $log)
                        <tr>
                            <td><input class="log-checkbox" type="checkbox" name="log_ids[]" value="{{ $log->id }}" aria-label="Select log {{ $log->id }}"></td>
                            <td>{{ $log->id }}</td>
                            <td><strong>{{ $log->user?->name ?? $log->user_name ?? 'Deleted User' }}</strong></td>
                            <td>{{ ucfirst(str_replace('_', ' ', $log->role ?? 'guest')) }}</td>
                            <td>{{ $log->action }}</td>
                            <td>{{ $log->module }}</td>
                            <td class="audit-description">{{ $log->description }}</td>
                            <td>{{ $log->ip_address ?? '—' }}</td>
                            <td>{{ $log->browser ?? 'Unknown' }}</td>
                            <td>{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
                            <td><a href="{{ route('admin.activity-logs.show', $log) }}">View</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="11" style="text-align:center;">No activity logs found.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </form>
    <div style="margin-top:20px;">{{ $logs->links() }}</div>
@endsection

@push('scripts')
<script>
    document.getElementById('selectAllLogs')?.addEventListener('change', (event) => {
        document.querySelectorAll('.log-checkbox').forEach((checkbox) => checkbox.checked = event.target.checked);
    });
</script>
@endpush
