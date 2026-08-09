<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request): View
    {
        $request->validate([
            'search' => ['nullable', 'string', 'max:255'],
            'user_id' => ['nullable', 'integer', 'exists:users,id'],
            'module' => ['nullable', 'string', 'max:100'],
            'action' => ['nullable', 'string', 'max:100'],
            'role' => ['nullable', 'string', 'max:50'],
            'severity' => ['nullable', 'in:normal,warning,suspicious'],
            'date_from' => ['nullable', 'date_format:Y-m-d'],
            'date_to' => ['nullable', 'date_format:Y-m-d', 'after_or_equal:date_from'],
        ]);

        $logs = ActivityLog::with('user')
            ->when($request->filled('search'), function ($query) use ($request): void {
                $search = $request->string('search')->toString();
                $query->where(function ($query) use ($search): void {
                    $query->where('user_name', 'like', "%{$search}%")
                        ->orWhere('user_email', 'like', "%{$search}%")
                        ->orWhere('target_name', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
                        ->orWhere('ip_address', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('user_id'), fn ($query) => $query->where('user_id', $request->integer('user_id')))
            ->when($request->filled('role'), fn ($query) => $query->where('role', $request->string('role')))
            ->when($request->filled('module'), fn ($query) => $query->where('module', $request->string('module')))
            ->when($request->filled('action'), fn ($query) => $query->where('action', $request->string('action')))
            ->when($request->filled('severity'), fn ($query) => $query->where('severity', $request->string('severity')))
            ->when($request->filled('date_from'), fn ($query) => $query->whereDate('created_at', '>=', $request->date('date_from')))
            ->when($request->filled('date_to'), fn ($query) => $query->whereDate('created_at', '<=', $request->date('date_to')))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.activity-logs.index', [
            'logs' => $logs,
            'users' => User::orderBy('name')->get(['id', 'name']),
            'modules' => ActivityLog::query()->distinct()->orderBy('module')->pluck('module'),
            'actions' => ActivityLog::query()->distinct()->orderBy('action')->pluck('action'),
            'roles' => ActivityLog::query()->whereNotNull('role')->distinct()->orderBy('role')->pluck('role'),
            'summary' => [
                'today' => ActivityLog::whereDate('created_at', today())->count(),
                'failed_logins' => ActivityLog::where('action', 'like', '%Failed Login%')->count(),
                'deletes' => ActivityLog::where('action', 'like', '%delete%')->count(),
                'role_changes' => ActivityLog::where('action', 'like', '%role%')->count(),
                'suspicious' => ActivityLog::where('severity', 'suspicious')->count(),
            ],
        ]);
    }

    public function show(ActivityLog $activityLog): View
    {
        return view('admin.activity-logs.show', ['log' => $activityLog->load('user')]);
    }

    public function destroySelected(Request $request): RedirectResponse
    {
        abort(405, 'Activity logs are append-only.');
    }

    public function clear(Request $request): RedirectResponse
    {
        $request->validate(['confirmation' => ['required', 'in:CLEAR']]);
        ActivityLog::query()->delete();

        return back()->with('success', 'All activity logs cleared successfully.');
    }
}
