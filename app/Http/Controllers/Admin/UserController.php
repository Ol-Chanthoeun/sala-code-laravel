<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserUpdateRequest;
use App\Models\RoleChangeLog;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $query = User::query()->latest();

        if ($request->filled('search')) {
            $query->where(function ($builder) use ($request): void {
                $builder->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $users = $query->paginate(15)->withQueryString();

        return view('admin.users.index', [
            'users' => $users,
            'roles' => $this->roles(),
        ]);
    }

    public function edit(User $user): View
    {
        $this->guardAdminCannotManageSuperAdmin($user);

        return view('admin.users.edit', [
            'managedUser' => $user,
            'roles' => $this->roles(),
        ]);
    }

    public function update(UserUpdateRequest $request, User $user): RedirectResponse
    {
        $this->guardAdminCannotManageSuperAdmin($user);
        $this->guardLastSuperAdminStatus($user, $request->validated('status'));

        $user->update($request->safe()->only(['name', 'email', 'status']));

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }

    public function updateRole(Request $request, User $user): RedirectResponse
    {
        abort_unless($request->user()?->isSuperAdmin(), 403);

        $validated = $request->validate([
            'role' => ['required', 'in:user,admin,super_admin'],
        ]);

        if (
            $request->user()->is($user)
            && $user->role === User::ROLE_SUPER_ADMIN
            && $validated['role'] !== User::ROLE_SUPER_ADMIN
            && ! $request->boolean('confirm_self_role_change')
        ) {
            return back()->withErrors([
                'role' => 'Confirm this change before removing your own Super Admin permission.',
            ]);
        }

        $this->guardLastSuperAdminRole($user, $validated['role']);

        $previousRole = $user->role;

        $user->update([
            'role' => $validated['role'],
        ]);

        if ($previousRole !== $validated['role']) {
            RoleChangeLog::create([
                'changed_by' => $request->user()->id,
                'user_id' => $user->id,
                'previous_role' => $previousRole,
                'new_role' => $validated['role'],
            ]);
        }

        return back()->with('success', 'User role updated successfully.');
    }

    public function toggleStatus(User $user): RedirectResponse
    {
        $this->guardAdminCannotManageSuperAdmin($user);

        $newStatus = $user->status === User::STATUS_ACTIVE
            ? User::STATUS_INACTIVE
            : User::STATUS_ACTIVE;

        $this->guardLastSuperAdminStatus($user, $newStatus);

        $user->update([
            'status' => $newStatus,
        ]);

        return back()->with('success', 'User status updated successfully.');
    }

    public function destroy(User $user): RedirectResponse
    {
        $this->guardAdminCannotManageSuperAdmin($user);

        if (auth()->user()->is($user)) {
            return back()->withErrors(['user' => 'You cannot delete your own account.']);
        }

        if ($user->isSuperAdmin() && User::where('role', User::ROLE_SUPER_ADMIN)->count() <= 1) {
            return back()->withErrors(['user' => 'The last Super Admin cannot be deleted.']);
        }

        $user->delete();

        return back()->with('success', 'User deleted successfully.');
    }

    /**
     * @return array<string, string>
     */
    private function roles(): array
    {
        return [
            User::ROLE_USER => 'User',
            User::ROLE_ADMIN => 'Admin',
            User::ROLE_SUPER_ADMIN => 'Super Admin',
        ];
    }

    private function guardAdminCannotManageSuperAdmin(User $user): void
    {
        if (auth()->user()?->isAdmin() && $user->isSuperAdmin()) {
            abort(403, 'Admins cannot edit, deactivate, or delete Super Admin accounts.');
        }
    }

    private function guardLastSuperAdminRole(User $user, string $newRole): void
    {
        if (
            $user->role === User::ROLE_SUPER_ADMIN
            && $newRole !== User::ROLE_SUPER_ADMIN
            && User::where('role', User::ROLE_SUPER_ADMIN)->count() <= 1
        ) {
            abort(403, 'The last Super Admin cannot be demoted.');
        }
    }

    private function guardLastSuperAdminStatus(User $user, string $newStatus): void
    {
        if (
            $user->isSuperAdmin()
            && $newStatus === User::STATUS_INACTIVE
            && User::where('role', User::ROLE_SUPER_ADMIN)->where('status', User::STATUS_ACTIVE)->count() <= 1
        ) {
            abort(403, 'The last active Super Admin cannot be deactivated.');
        }
    }
}
