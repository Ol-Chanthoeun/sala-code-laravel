<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminStoreRequest;
use App\Http\Requests\Admin\AdminUpdateRequest;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;

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
        User::create([
            'name' => $request->validated('name'),
            'email' => $request->validated('email'),
            'password' => Hash::make($request->validated('password')),
            'role' => User::ROLE_ADMIN,
            'status' => $request->validated('status'),
        ]);

        return redirect()
            ->route('admin.admins.index')
            ->with('success', 'Admin account created successfully.');
    }

    public function edit(User $admin): View
    {
        abort_if($admin->isSuperAdmin(), 403, 'Super Admin accounts are protected here. Use User Management for role changes.');

        return view('admin.admins.edit', compact('admin'));
    }

    public function update(AdminUpdateRequest $request, User $admin): RedirectResponse
    {
        abort_if($admin->isSuperAdmin(), 403, 'Super Admin accounts are protected here.');

        $data = $request->safe()->only(['name', 'email', 'status']);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->validated('password'));
        }

        $admin->update($data);

        return redirect()
            ->route('admin.admins.index')
            ->with('success', 'Admin account updated successfully.');
    }

    public function destroy(User $admin): RedirectResponse
    {
        abort_if($admin->isSuperAdmin(), 403, 'Super Admin accounts cannot be deleted from Admin Management.');

        $admin->delete();

        return back()->with('success', 'Admin account deleted successfully.');
    }
}
