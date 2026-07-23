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
            'date' => ['nullable', 'date_format:Y-m-d'],
        ]);

        $logs = ActivityLog::with('user')
            ->when($request->filled('search'), function ($query) use ($request): void {
                $search = $request->string('search')->toString();
                $query->where(function ($query) use ($search): void {
                    $query->where('user_name', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
                        ->orWhere('ip_address', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('user_id'), fn ($query) => $query->where('user_id', $request->integer('user_id')))
            ->when($request->filled('module'), fn ($query) => $query->where('module', $request->string('module')))
            ->when($request->filled('action'), fn ($query) => $query->where('action', $request->string('action')))
            ->when($request->filled('date'), fn ($query) => $query->whereDate('created_at', $request->string('date')))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.activity-logs.index', [
            'logs' => $logs,
            'users' => User::orderBy('name')->get(['id', 'name']),
            'modules' => ActivityLog::query()->distinct()->orderBy('module')->pluck('module'),
            'actions' => ActivityLog::query()->distinct()->orderBy('action')->pluck('action'),
        ]);
    }

    public function show(ActivityLog $activityLog): View
    {
        return view('admin.activity-logs.show', ['log' => $activityLog->load('user')]);
    }

    public function destroySelected(Request $request): RedirectResponse
    {
        $validated = $request->validate(['log_ids' => ['required', 'array'], 'log_ids.*' => ['integer', 'exists:activity_logs,id']]);
        ActivityLog::whereKey($validated['log_ids'])->delete();

        return back()->with('success', 'Selected activity logs deleted successfully.');
    }

    public function clear(): RedirectResponse
    {
        ActivityLog::query()->delete();

        return back()->with('success', 'All activity logs cleared successfully.');
    }
}
