@extends('layouts.admin')

@section('title', 'Activity Log Details')
@section('page-title', 'Activity Log Details')
@section('breadcrumb', 'Activity Logs / Details')

@section('content')
    <div class="system-info">
        <div class="section-title">Log #{{ $log->id }}</div>
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:18px;margin-top:18px;">
            <p><strong>User</strong><br>{{ $log->user?->name ?? $log->user_name ?? 'Deleted User' }}</p>
            <p><strong>Role</strong><br>{{ ucfirst(str_replace('_', ' ', $log->role ?? 'guest')) }}</p>
            <p><strong>Action</strong><br>{{ $log->action }}</p>
            <p><strong>Module</strong><br>{{ $log->module }}</p>
            <p><strong>IP Address</strong><br>{{ $log->ip_address ?? '—' }}</p>
            <p><strong>Browser</strong><br>{{ $log->browser ?? 'Unknown' }}</p>
            <p><strong>HTTP Method</strong><br>{{ $log->method ?? '—' }}</p>
            <p><strong>Route</strong><br>{{ $log->route_name ?? '—' }}</p>
            <p><strong>Date & Time</strong><br>{{ $log->created_at->format('Y-m-d H:i:s') }}</p>
        </div>
        <p style="margin-top:20px;"><strong>Description</strong><br>{{ $log->description }}</p>
        <p style="margin-top:20px;overflow-wrap:anywhere;"><strong>User Agent</strong><br>{{ $log->user_agent ?? '—' }}</p>
        @if($log->context)<p style="margin-top:20px;"><strong>Context</strong></p><pre style="margin-top:8px;overflow:auto;padding:14px;background:#f8fafc;border-radius:7px;">{{ json_encode($log->context, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>@endif
        <a class="action-btn" style="width:180px;margin-top:22px;" href="{{ route('admin.activity-logs.index') }}">Back to Logs</a>
    </div>
@endsection
