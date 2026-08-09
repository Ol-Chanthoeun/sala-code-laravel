<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserUpdateRequest;
use App\Models\RoleChangeLog;
use App\Models\User;
use App\Services\UserSecurityLogger;
use App\Services\ActivityLogService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\Rule;
use Throwable;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        Gate::authorize('viewAny', User::class);
        $query = User::query()->latest();
        $query->when($request->filled('search'), fn ($query) => $query->where(fn ($nested) => $nested
            ->where('name', 'like', '%' . $request->search . '%')->orWhere('email', 'like', '%' . $request->search . '%')));
        $query->when($request->filled('role'), fn ($query) => $query->where('role', $request->role));

        return view('admin.users.index', ['users' => $query->paginate(15)->withQueryString(), 'roles' => $this->roles()]);
    }

    public function edit(User $user): View
    {
        Gate::authorize('update', $user);
        return view('admin.users.edit', ['managedUser' => $user, 'roles' => $this->roles()]);
    }

    public function update(UserUpdateRequest $request, User $user): RedirectResponse
    {
        $beforeStatus = $user->status;
        $user->update($request->safe()->only(['name', 'email', 'status']));
        $action = $beforeStatus !== $user->status
            ? ($user->isActive() ? 'activated user' : 'deactivated user')
            : 'edited user';
        UserSecurityLogger::record($request, $request->user(), $user, $action, ['previous_status' => $beforeStatus, 'new_status' => $user->status]);

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    public function updateRole(Request $request, User $user): RedirectResponse
    {
        Gate::authorize('changeRole', $user);
        $validated = $request->validate(['role' => ['required', Rule::in([User::ROLE_USER, User::ROLE_ADMIN])]]);
        $previousRole = $user->role;
        if ($previousRole === $validated['role']) return back()->with('success', 'The user already has that role.');

        DB::transaction(function () use ($request, $user, $validated, $previousRole): void {
            $user->update(['role' => $validated['role']]);
            RoleChangeLog::create([
                'changed_by' => $request->user()->id, 'user_id' => $user->id,
                'previous_role' => $previousRole, 'new_role' => $validated['role'],
            ]);
            UserSecurityLogger::record($request, $request->user(), $user, 'changed role', [
                'previous_role' => $previousRole, 'new_role' => $validated['role'],
            ]);
        });

        return back()->with('success', 'User role updated successfully.');
    }

    public function toggleStatus(Request $request, User $user): RedirectResponse
    {
        Gate::authorize('changeStatus', $user);
        $previousStatus = $user->status;
        $user->update(['status' => $user->isActive() ? User::STATUS_INACTIVE : User::STATUS_ACTIVE]);
        UserSecurityLogger::record($request, $request->user(), $user, $user->isActive() ? 'activated user' : 'deactivated user', [
            'previous_status' => $previousStatus, 'new_status' => $user->status,
        ]);
        return back()->with('success', 'User status updated successfully.');
    }

    public function sendPasswordReset(Request $request, User $user): RedirectResponse
    {
        abort_unless($request->user()->isSuperAdmin(), 403);

        if ($user->usesGoogleAuthentication()) {
            return redirect()->route('admin.users.index')->withErrors([
                'password_reset' => 'Google accounts do not use Sala Code password reset.',
            ]);
        }

        Gate::authorize('sendPasswordReset', $user);

        try {
            $status = Password::sendResetLink(['email' => $user->email]);
        } catch (Throwable $exception) {
            report($exception);
            ActivityLogService::log($request,'Failed Sensitive Action','Security',$user,'Password reset email could not be sent.');

            return redirect()->route('admin.users.index')->withErrors([
                'password_reset' => 'The reset email could not be sent. Please check the mail server configuration and try again.',
            ]);
        }

        if ($status !== Password::RESET_LINK_SENT) {
            return redirect()->route('admin.users.index')->withErrors(['password_reset' => __($status)]);
        }

        UserSecurityLogger::record($request, $request->user(), $user, 'sent password reset email');

        return redirect()->route('admin.users.index')->with('success', 'Password reset link sent successfully.');
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        Gate::authorize('delete', $user);
        UserSecurityLogger::record($request, $request->user(), $user, $user->isAdmin() ? 'deleted admin' : 'deleted user');
        $user->delete();
        return back()->with('success', 'User deleted permanently.');
    }

    private function roles(): array
    {
        return [User::ROLE_USER => 'User', User::ROLE_ADMIN => 'Admin', User::ROLE_SUPER_ADMIN => 'Super Admin'];
    }
}
