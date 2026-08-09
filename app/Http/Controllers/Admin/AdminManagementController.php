<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminStoreRequest;
use App\Http\Requests\Admin\AdminUpdateRequest;
use App\Models\User;
use App\Services\UserSecurityLogger;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Gate;

class AdminManagementController extends Controller
{
    public function index(): View
    {
        $admins = User::whereIn('role', [User::ROLE_ADMIN, User::ROLE_SUPER_ADMIN])
            ->latest()
            ->paginate(15);

        return view('admin.admins.index', compact('admins'));
    }

    public function create(): View
    {
        return view('admin.admins.create');
    }

    public function store(AdminStoreRequest $request): RedirectResponse
    {
        $admin = User::create([
            'name' => $request->validated('name'),
            'email' => $request->validated('email'),
            'password' => Hash::make($request->validated('password')),
            'role' => User::ROLE_ADMIN,
            'status' => $request->validated('status'),
        ]);
        UserSecurityLogger::record($request, $request->user(), $admin, 'created admin');

        return redirect()
            ->route('admin.admins.index')
            ->with('success', 'Admin account created successfully.');
    }

    public function edit(User $admin): View
    {
        Gate::authorize('update', $admin);

        return view('admin.admins.edit', compact('admin'));
    }

    public function update(AdminUpdateRequest $request, User $admin): RedirectResponse
    {
        Gate::authorize('update', $admin);

        $data = $request->safe()->only(['name', 'email', 'status']);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->validated('password'));
        }

        $previousStatus = $admin->status;
        $admin->update($data);
        UserSecurityLogger::record($request, $request->user(), $admin, $previousStatus !== $admin->status
            ? ($admin->isActive() ? 'activated admin' : 'deactivated admin')
            : 'edited admin', ['previous_status' => $previousStatus, 'new_status' => $admin->status]);

        return redirect()
            ->route('admin.admins.index')
            ->with('success', 'Admin account updated successfully.');
    }

    public function destroy(Request $request, User $admin): RedirectResponse
    {
        Gate::authorize('delete', $admin);
        UserSecurityLogger::record($request, $request->user(), $admin, 'deleted admin');
        $admin->delete();

        return back()->with('success', 'Admin account deleted successfully.');
    }
}
