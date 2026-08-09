@extends('layouts.admin')
@section('title', 'Activity Logs')
@section('page-title', 'Activity Logs')
@section('breadcrumb', 'Security / Activity Logs')

@push('styles')
<style>
.audit-summary{display:grid;gap:12px;grid-template-columns:repeat(5,minmax(0,1fr));margin-bottom:18px}.audit-summary article{background:#fff;border-radius:12px;box-shadow:0 3px 10px rgba(15,23,42,.06);padding:15px}.audit-summary span{color:#64748b;font-size:12px;font-weight:700}.audit-summary strong{display:block;font-size:24px;margin-top:3px}.audit-filter-card{background:#fff;border:1px solid #e2e8f0;border-radius:12px;margin-bottom:20px;padding:14px}.audit-toolbar{align-items:end;display:grid;gap:11px;grid-template-columns:2fr repeat(7,minmax(112px,1fr)) auto}.audit-toolbar label{color:#374151;display:grid;font-size:12px;font-weight:700;gap:6px}.audit-toolbar input,.audit-toolbar select{border:1px solid #d1d5db;border-radius:7px;min-width:0;padding:9px;width:100%}.audit-actions{align-items:center;display:flex;gap:9px;margin-top:12px}.audit-button{align-items:center;background:#4f46e5;border:0;border-radius:7px;color:#fff;cursor:pointer;display:inline-flex;font-weight:700;gap:7px;padding:9px 12px;text-decoration:none}.audit-button.secondary{background:#64748b}.audit-button.danger{background:#dc2626;margin-left:auto}
.audit-table-scroll{max-height:600px;overflow:auto}.audit-table{table-layout:fixed;width:100%}.audit-table th,.audit-table td{overflow:hidden;padding-left:10px;padding-right:10px;vertical-align:middle}.audit-table thead th{background:#fff;box-shadow:0 1px 0 #dbe2ea;position:sticky;top:0;z-index:3}.audit-table th:nth-child(1){width:5%}.audit-table th:nth-child(2){width:18%}.audit-table th:nth-child(3){width:13%}.audit-table th:nth-child(4){width:13%}.audit-table th:nth-child(5){width:20%}.audit-table th:nth-child(6){width:9%}.audit-table th:nth-child(7){width:15%}.audit-table th:nth-child(8){width:7%}.audit-actor strong,.audit-actor small{display:block;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}.audit-actor small{color:#64748b;font-size:12px;margin-top:2px}.audit-target{display:block;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}.audit-date{line-height:1.3;white-space:nowrap}.audit-date small{color:#64748b;display:block;margin-top:2px}.audit-details-cell{text-align:center}.audit-badge{border-radius:999px;display:inline-flex;font-size:11px;font-weight:800;line-height:1.2;padding:4px 8px;white-space:nowrap}.audit-badge--create{background:#dcfce7;color:#15803d}.audit-badge--update{background:#dbeafe;color:#1d4ed8}.audit-badge--delete{background:#fee2e2;color:#b91c1c}.audit-badge--login{background:#ede9fe;color:#5b21b6}.audit-badge--logout{background:#f1f5f9;color:#475569}.audit-badge--role{background:#ffedd5;color:#c2410c}.audit-badge--export{background:#f3e8ff;color:#7e22ce}.audit-badge--denied{background:#ffedd5;color:#c2410c}.audit-badge--default{background:#f1f5f9;color:#475569}.audit-risk--normal{background:#ecfdf5;color:#047857}.audit-risk--warning{background:#fff7ed;color:#c2410c}.audit-risk--suspicious{background:#fee2e2;color:#b91c1c}
.audit-mobile-list{display:none}.audit-mobile-card{border-bottom:1px solid #e2e8f0;padding:14px 16px}.audit-mobile-card:last-child{border-bottom:0}.audit-mobile-card__top,.audit-mobile-card__footer{align-items:center;display:flex;gap:8px;justify-content:space-between}.audit-mobile-card__actor{min-width:0}.audit-mobile-card__actor strong,.audit-mobile-card__actor small{display:block;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}.audit-mobile-card__actor small{color:#64748b;font-size:12px;margin-top:2px}.audit-mobile-card__badges{align-items:center;display:flex;flex-shrink:0;gap:6px}.audit-mobile-card__target{color:#334155;margin:12px 0;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}.audit-mobile-card__target span{color:#64748b;font-size:11px;font-weight:800;text-transform:uppercase}.audit-mobile-card__footer{color:#64748b;font-size:12px}
.admin-confirm-dialog .audit-details-modal{max-height:80vh;max-width:600px;overflow:hidden;padding:0;width:min(600px,calc(100vw - 32px))}.audit-details-body{max-height:calc(80vh - 126px);overflow-y:auto;padding:18px}.audit-details-grid{display:grid;gap:10px;grid-template-columns:repeat(2,minmax(0,1fr))}.audit-details-grid div{background:#f8fafc;border:1px solid #eef2f7;border-radius:8px;padding:10px}.audit-details-grid .audit-detail-wide{grid-column:1/-1}.audit-details-grid dt{color:#64748b;font-size:11px;font-weight:700;text-transform:uppercase}.audit-details-grid dd{font-weight:600;margin:4px 0 0;overflow-wrap:anywhere;white-space:pre-wrap}.audit-changes{margin-top:16px}.audit-changes h4{margin:0 0 8px}.audit-change-row{background:#f8fafc;border-bottom:1px solid #e2e8f0;display:grid;gap:8px;grid-template-columns:minmax(90px,.7fr) 1fr 1fr;padding:9px}.audit-change-row:first-child{background:#eef2ff;border-radius:8px 8px 0 0;color:#4338ca}.audit-change-row:last-child{border-radius:0 0 8px 8px}.audit-change-row span{overflow-wrap:anywhere;white-space:pre-wrap}.audit-change-row span b{display:none}.clear-confirm-input{border:1px solid #cbd5e1;border-radius:8px;margin-top:10px;padding:10px;width:100%}.audit-back-to-top{align-items:center;background:#4f46e5;border:0;border-radius:50%;bottom:24px;box-shadow:0 8px 22px rgba(15,23,42,.22);color:#fff;cursor:pointer;display:flex;height:40px;justify-content:center;opacity:0;pointer-events:none;position:fixed;right:24px;transform:translateY(8px);transition:.2s;visibility:hidden;width:40px;z-index:20}.audit-back-to-top.is-visible{opacity:1;pointer-events:auto;transform:none;visibility:visible}
@media(max-width:1200px){.audit-toolbar{grid-template-columns:repeat(3,minmax(0,1fr))}.audit-summary{grid-template-columns:repeat(2,1fr)}}
@media(max-width:900px) and (min-width:641px){.audit-module-column{display:none}.audit-table th:nth-child(1){width:6%}.audit-table th:nth-child(2){width:22%}.audit-table th:nth-child(3){width:16%}.audit-table th:nth-child(5){width:24%}.audit-table th:nth-child(6){width:11%}.audit-table th:nth-child(7){width:16%}.audit-table th:nth-child(8){width:5%}}
@media(max-width:640px){.audit-toolbar,.audit-summary,.audit-details-grid{grid-template-columns:1fr}.audit-actions{flex-wrap:wrap}.audit-button.danger{margin-left:0}.audit-table-scroll{display:none}.audit-mobile-list{display:block}.audit-mobile-card__top{align-items:flex-start;flex-direction:column}.audit-change-row{grid-template-columns:1fr}.audit-change-row:first-child{display:none}.audit-change-row span b{display:inline}.audit-details-body{padding:14px}.audit-back-to-top{bottom:72px;height:36px;right:16px;width:36px}}
</style>
@endpush

@section('content')
@if(session('success'))<p style="color:green;margin-bottom:15px;">{{ session('success') }}</p>@endif
@if($errors->any())<p style="color:#dc2626;margin-bottom:15px;">{{ $errors->first() }}</p>@endif

<div class="audit-summary">
    <article><span>Today's Activities</span><strong>{{ number_format($summary['today']) }}</strong></article>
    <article><span>Failed Logins</span><strong>{{ number_format($summary['failed_logins']) }}</strong></article>
    <article><span>Delete Actions</span><strong>{{ number_format($summary['deletes']) }}</strong></article>
    <article><span>Role Changes</span><strong>{{ number_format($summary['role_changes']) }}</strong></article>
    <article><span>Suspicious Activities</span><strong>{{ number_format($summary['suspicious']) }}</strong></article>
</div>

<div class="audit-filter-card">
    <form class="audit-toolbar" method="GET" action="{{ route('admin.activity-logs.index') }}">
        <label>Search<input name="search" value="{{ request('search') }}" placeholder="Name, email, target, description, IP"></label>
        <label>User<select name="user_id"><option value="">All users</option>@foreach($users as $user)<option value="{{ $user->id }}" @selected((string) request('user_id') === (string) $user->id)>{{ $user->name }}</option>@endforeach</select></label>
        <label>Role<select name="role"><option value="">All roles</option>@foreach($roles as $role)<option value="{{ $role }}" @selected(request('role') === $role)>{{ Str::headline($role) }}</option>@endforeach</select></label>
        <label>Module<select name="module"><option value="">All modules</option>@foreach($modules as $module)<option value="{{ $module }}" @selected(request('module') === $module)>{{ $module }}</option>@endforeach</select></label>
        <label>Action<select name="action"><option value="">All actions</option>@foreach($actions as $action)<option value="{{ $action }}" @selected(request('action') === $action)>{{ Str::headline($action) }}</option>@endforeach</select></label>
        <label>Risk Level<select name="severity"><option value="">All risk levels</option>@foreach(['normal','warning','suspicious'] as $risk)<option value="{{ $risk }}" @selected(request('severity') === $risk)>{{ Str::headline($risk) }}</option>@endforeach</select></label>
        <label>Date From<input type="date" name="date_from" value="{{ request('date_from') }}"></label>
        <label>Date To<input type="date" name="date_to" value="{{ request('date_to') }}"></label>
        <button class="audit-button" type="submit"><i class="fas fa-filter"></i> Filter</button>
    </form>
    <div class="audit-actions">
        @include('admin.reports._quick-export', ['reportType' => 'activity-logs'])
        <a class="audit-button secondary" href="{{ route('admin.activity-logs.index') }}"><i class="fas fa-rotate-left"></i> Reset</a>
        <button class="audit-button danger" id="openClearLogs" type="button"><i class="fas fa-broom"></i> Clear All Logs</button>
    </div>
</div>

@php
    $activityPresentation = static function ($log): array {
        $action = Str::lower($log->action);
        $tone = match (true) {
            Str::contains($action, ['permission denied', 'denied']) => 'denied',
            Str::contains($action, 'delete') => 'delete',
            Str::contains($action, 'create') => 'create',
            Str::contains($action, 'role') => 'role',
            Str::contains($action, 'export') => 'export',
            Str::contains($action, 'logout') => 'logout',
            Str::contains($action, 'login') => 'login',
            Str::contains($action, 'update') => 'update',
            default => 'default',
        };

        return [$tone, [
            'id' => $log->id, 'actor' => $log->user_name ?: 'Guest', 'email' => $log->actor_email,
            'role' => Str::headline($log->role ?: 'guest'), 'action' => Str::headline($log->action),
            'module' => $log->module, 'target' => $log->target_label, 'description' => $log->description,
            'ip' => $log->ip_address, 'browser' => $log->browser, 'device' => $log->device,
            'risk' => Str::headline($log->severity ?: 'normal'), 'date' => $log->created_at->format('M j, Y g:i:s A'),
            'old' => $log->old_values, 'new' => $log->new_values,
        ]];
    };
@endphp

<div class="data-table">
    <div class="table-header"><h3>Audit Trail</h3></div>
    <div class="table-responsive audit-table-scroll">
        <table class="audit-table">
            <thead><tr><th>ID</th><th>Actor</th><th>Action</th><th class="audit-module-column">Module</th><th>Target</th><th>Risk</th><th>Date & Time</th><th>Details</th></tr></thead>
            <tbody>
            @forelse($logs as $log)
                @php
                    [$tone, $detailPayload] = $activityPresentation($log);
                @endphp
                <tr>
                    <td>{{ $log->id }}</td>
                    <td class="audit-actor"><strong>{{ $detailPayload['actor'] }}</strong><small title="{{ $log->actor_email ?: '-' }}">{{ $log->actor_email ?: '-' }}</small></td>
                    <td><span class="audit-badge audit-badge--{{ $tone }}">{{ Str::headline($log->action) }}</span></td>
                    <td class="audit-module-column">{{ $log->module ?: '-' }}</td>
                    <td><span class="audit-target" title="{{ $log->target_label ?: '-' }}">{{ $log->target_label ?: '-' }}</span></td>
                    <td><span class="audit-badge audit-risk--{{ $log->severity ?: 'normal' }}">{{ Str::headline($log->severity ?: 'normal') }}</span></td>
                    <td class="audit-date">{{ $log->created_at->format('M j, Y') }}<small>{{ $log->created_at->format('g:i A') }}</small></td>
                    <td class="audit-details-cell"><button class="admin-icon-btn admin-icon-btn--primary audit-detail-trigger" type="button" title="View activity details" aria-label="View activity details" data-log="{{ json_encode($detailPayload) }}"><i class="fas fa-eye" aria-hidden="true"></i></button></td>
                </tr>
            @empty
                <tr><td colspan="8" style="text-align:center;">No activity logs found.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="audit-mobile-list">
        @forelse($logs as $log)
            @php
                [$tone, $detailPayload] = $activityPresentation($log);
            @endphp
            <article class="audit-mobile-card">
                <div class="audit-mobile-card__top"><div class="audit-mobile-card__actor"><strong>{{ $detailPayload['actor'] }}</strong><small>{{ $log->actor_email ?: '-' }}</small></div><div class="audit-mobile-card__badges"><span class="audit-badge audit-badge--{{ $tone }}">{{ Str::headline($log->action) }}</span><span class="audit-badge audit-risk--{{ $log->severity ?: 'normal' }}">{{ Str::headline($log->severity ?: 'normal') }}</span></div></div>
                <div class="audit-mobile-card__target" title="{{ $log->target_label ?: '-' }}"><span>Target</span><br>{{ $log->target_label ?: '-' }}</div>
                <div class="audit-mobile-card__footer"><time datetime="{{ $log->created_at->toIso8601String() }}">{{ $log->created_at->format('M j, Y · g:i A') }}</time><button class="admin-icon-btn admin-icon-btn--primary audit-detail-trigger" type="button" title="View activity details" aria-label="View activity details" data-log="{{ json_encode($detailPayload) }}"><i class="fas fa-eye" aria-hidden="true"></i></button></div>
            </article>
        @empty
            <p style="padding:20px;text-align:center;">No activity logs found.</p>
        @endforelse
    </div>
</div>
{{ $logs->links('admin.partials.pagination') }}

<dialog class="admin-confirm-dialog" id="activityDetailsDialog" aria-labelledby="activityDetailsTitle">
    <div class="admin-confirm-dialog__panel admin-details-modal__panel audit-details-modal">
        <div class="admin-confirm-dialog__header admin-details-modal__header"><div class="admin-confirm-dialog__title"><i class="fas fa-shield-halved" aria-hidden="true"></i><h3 id="activityDetailsTitle">Activity Details</h3></div><button class="admin-confirm-dialog__close" type="button" data-close-details aria-label="Close activity details">&times;</button></div>
        <div class="audit-details-body"><dl class="audit-details-grid" id="auditDetailsGrid"></dl><section class="audit-changes" id="auditChanges" hidden><h4>Changes</h4><div id="auditChangeRows"></div></section></div>
        <div class="admin-confirm-dialog__actions admin-details-modal__footer"><button class="admin-confirm-dialog__cancel" type="button" data-close-details>Close</button></div>
    </div>
</dialog>

<dialog class="admin-confirm-dialog" id="clearLogsDialog"><div class="admin-confirm-dialog__panel"><div class="admin-confirm-dialog__header"><div class="admin-confirm-dialog__title"><i class="fas fa-triangle-exclamation" style="color:#dc2626"></i><h3>Clear Activity Logs?</h3></div><button class="admin-confirm-dialog__close" type="button" data-close-clear>&times;</button></div><p>This will permanently remove audit history. Export or archive it first if it must be retained.</p><form action="{{ route('admin.activity-logs.clear') }}" method="POST">@csrf @method('DELETE')<label>Type <strong>CLEAR</strong> to continue<input class="clear-confirm-input" id="clearLogsInput" name="confirmation" autocomplete="off"></label><div class="admin-confirm-dialog__actions"><button class="admin-confirm-dialog__cancel" type="button" data-close-clear>Cancel</button><button class="admin-confirm-dialog__confirm" id="confirmClearLogs" type="submit" disabled>Clear Activity Logs</button></div></form></div></dialog>
<button class="audit-back-to-top" id="auditBackToTop" type="button" title="Back to top" aria-label="Back to top"><i class="fas fa-arrow-up"></i></button>
@endsection

@push('scripts')
<script>
(() => {
    const dialog = document.getElementById('activityDetailsDialog');
    const grid = document.getElementById('auditDetailsGrid');
    const changes = document.getElementById('auditChanges');
    const rows = document.getElementById('auditChangeRows');
    const esc = value => String(value ?? '-').replace(/[&<>'"]/g, char => ({'&':'&amp;','<':'&lt;','>':'&gt;',"'":'&#039;','"':'&quot;'}[char]));
    const display = value => value === null || value === undefined || value === '' ? '-' : (typeof value === 'object' ? JSON.stringify(value, null, 2) : String(value));
    const field = (label, value, wide = false) => `<div${wide ? ' class="audit-detail-wide"' : ''}><dt>${esc(label)}</dt><dd>${esc(display(value))}</dd></div>`;

    document.querySelectorAll('.audit-detail-trigger').forEach(button => button.addEventListener('click', () => {
        const log = JSON.parse(button.dataset.log);
        grid.innerHTML = field('Actor', log.actor) + field('Actor Email', log.email) + field('Role', log.role) + field('Action', log.action) + field('Module', log.module) + field('Risk Level', log.risk) + field('Target', log.target, true) + field('Description', log.description, true) + field('IP Address', log.ip) + field('Browser', log.browser) + field('Device', log.device) + field('Date & Time', log.date);
        const keys = [...new Set([...Object.keys(log.old || {}), ...Object.keys(log.new || {})])];
        rows.innerHTML = keys.length ? '<div class="audit-change-row"><strong>Field</strong><strong>Before</strong><strong>After</strong></div>' + keys.map(key => `<div class="audit-change-row"><strong>${esc(key.replaceAll('_', ' '))}</strong><span><b>Before: </b>${esc(display(log.old?.[key]))}</span><span><b>After: </b>${esc(display(log.new?.[key]))}</span></div>`).join('') : '';
        changes.hidden = !keys.length;
        dialog.showModal();
    }));
    document.querySelectorAll('[data-close-details]').forEach(button => button.addEventListener('click', () => dialog.close()));
    dialog.addEventListener('click', event => { if (event.target === dialog) dialog.close(); });

    const clear = document.getElementById('clearLogsDialog');
    const input = document.getElementById('clearLogsInput');
    const confirm = document.getElementById('confirmClearLogs');
    document.getElementById('openClearLogs').addEventListener('click', () => { input.value = ''; confirm.disabled = true; clear.showModal(); });
    input.addEventListener('input', () => confirm.disabled = input.value !== 'CLEAR');
    document.querySelectorAll('[data-close-clear]').forEach(button => button.addEventListener('click', () => clear.close()));

    const top = document.getElementById('auditBackToTop');
    const toggleTop = () => top.classList.toggle('is-visible', window.scrollY >= 380);
    window.addEventListener('scroll', toggleTop, { passive: true });
    top.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));
    toggleTop();
})();
</script>
@endpush
