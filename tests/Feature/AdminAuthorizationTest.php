<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class AdminAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_normal_user_cannot_access_admin_dashboard(): void
    {
        $user = User::factory()->create([
            'role' => User::ROLE_USER,
            'status' => User::STATUS_ACTIVE,
        ]);

        $this->actingAs($user)
            ->get('/admin')
            ->assertForbidden();
    }

    public function test_admin_can_access_course_and_lesson_management(): void
    {
        $admin = User::factory()->create([
            'role' => User::ROLE_ADMIN,
            'status' => User::STATUS_ACTIVE,
        ]);

        $this->actingAs($admin)
            ->get(route('admin.courses.index'))
            ->assertOk();

        $this->actingAs($admin)
            ->get(route('admin.lessons.index'))
            ->assertOk();
    }

    public function test_admin_cannot_access_admin_management_or_change_roles(): void
    {
        $admin = User::factory()->create([
            'role' => User::ROLE_ADMIN,
            'status' => User::STATUS_ACTIVE,
        ]);

        $target = User::factory()->create([
            'role' => User::ROLE_USER,
            'status' => User::STATUS_ACTIVE,
        ]);

        $this->actingAs($admin)
            ->get(route('admin.admins.index'))
            ->assertForbidden();

        $this->actingAs($admin)
            ->patch(route('admin.users.update-role', $target), [
                'role' => User::ROLE_ADMIN,
            ])
            ->assertForbidden();
    }

    public function test_super_admin_can_promote_user_to_admin(): void
    {
        $superAdmin = User::factory()->create([
            'role' => User::ROLE_SUPER_ADMIN,
            'status' => User::STATUS_ACTIVE,
        ]);

        $target = User::factory()->create([
            'role' => User::ROLE_USER,
            'status' => User::STATUS_ACTIVE,
        ]);

        $this->actingAs($superAdmin)
            ->patch(route('admin.users.update-role', $target), [
                'role' => User::ROLE_ADMIN,
            ])
            ->assertSessionHasNoErrors()
            ->assertRedirect();

        $this->assertSame(User::ROLE_ADMIN, $target->fresh()->role);
    }

    public function test_last_super_admin_cannot_be_demoted(): void
    {
        User::where('role', User::ROLE_SUPER_ADMIN)->delete();

        $superAdmin = User::factory()->create([
            'role' => User::ROLE_SUPER_ADMIN,
            'status' => User::STATUS_ACTIVE,
        ]);

        $this->actingAs($superAdmin)
            ->patch(route('admin.users.update-role', $superAdmin), [
                'role' => User::ROLE_ADMIN,
                'confirm_self_role_change' => '1',
            ])
            ->assertForbidden();

        $this->assertSame(User::ROLE_SUPER_ADMIN, $superAdmin->fresh()->role);
    }

    public function test_admin_can_manage_normal_users_but_not_admin_accounts(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $user = User::factory()->create(['role' => User::ROLE_USER]);
        $otherAdmin = User::factory()->create(['role' => User::ROLE_ADMIN]);

        $this->actingAs($admin)->patch(route('admin.users.toggle-status', $user))->assertRedirect();
        $this->assertSame(User::STATUS_INACTIVE, $user->fresh()->status);

        $this->actingAs($admin)->patch(route('admin.users.toggle-status', $otherAdmin))->assertForbidden();
        $this->actingAs($admin)->delete(route('admin.users.destroy', $otherAdmin))->assertForbidden();
        $this->actingAs($admin)->put(route('admin.users.update', $otherAdmin), [
            'name' => 'Changed', 'email' => $otherAdmin->email, 'status' => User::STATUS_ACTIVE,
        ])->assertForbidden();
        $this->assertSame(User::STATUS_ACTIVE, $otherAdmin->fresh()->status);
    }

    public function test_no_admin_can_deactivate_delete_or_change_its_own_role(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $this->actingAs($admin)->patch(route('admin.users.toggle-status', $admin))->assertForbidden();
        $this->actingAs($admin)->delete(route('admin.users.destroy', $admin))->assertForbidden();

        $superAdmin = User::factory()->create(['role' => User::ROLE_SUPER_ADMIN]);
        $this->actingAs($superAdmin)->patch(route('admin.users.toggle-status', $superAdmin))->assertForbidden();
        $this->actingAs($superAdmin)->delete(route('admin.users.destroy', $superAdmin))->assertForbidden();
        $this->actingAs($superAdmin)->patch(route('admin.users.update-role', $superAdmin), ['role' => User::ROLE_ADMIN])->assertForbidden();
    }

    public function test_primary_super_admin_is_protected_from_every_user_management_mutation(): void
    {
        $actor = User::factory()->create(['role' => User::ROLE_SUPER_ADMIN]);
        $primary = User::factory()->create([
            'email' => User::PRIMARY_SUPER_ADMIN_EMAIL, 'role' => User::ROLE_SUPER_ADMIN, 'status' => User::STATUS_ACTIVE,
        ]);

        $this->actingAs($actor)->patch(route('admin.users.toggle-status', $primary))->assertForbidden();
        $this->actingAs($actor)->delete(route('admin.users.destroy', $primary))->assertForbidden();
        $this->actingAs($actor)->post(route('admin.users.password-reset', $primary))->assertForbidden();
        $this->actingAs($actor)->put(route('admin.users.update', $primary), [
            'name' => 'Changed', 'email' => $primary->email, 'status' => User::STATUS_ACTIVE,
        ])->assertForbidden();
    }

    public function test_super_admin_can_manage_admin_and_role_values_are_restricted(): void
    {
        $superAdmin = User::factory()->create(['role' => User::ROLE_SUPER_ADMIN]);
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $user = User::factory()->create(['role' => User::ROLE_USER]);

        $this->actingAs($superAdmin)->patch(route('admin.users.toggle-status', $admin))->assertRedirect();
        $this->assertSame(User::STATUS_INACTIVE, $admin->fresh()->status);

        $this->actingAs($superAdmin)->patch(route('admin.users.update-role', $user), ['role' => User::ROLE_SUPER_ADMIN])
            ->assertSessionHasErrors('role');
        $this->assertSame(User::ROLE_USER, $user->fresh()->role);
    }

    public function test_security_sensitive_user_actions_include_target_in_activity_log(): void
    {
        $superAdmin = User::factory()->create(['role' => User::ROLE_SUPER_ADMIN]);
        $user = User::factory()->create(['role' => User::ROLE_USER]);

        $this->actingAs($superAdmin)->patch(route('admin.users.update-role', $user), ['role' => User::ROLE_ADMIN])->assertRedirect();

        $log = ActivityLog::where('action', 'Role Change')->latest()->firstOrFail();
        $this->assertSame($superAdmin->id, $log->user_id);
        $this->assertSame($user->id, $log->context['target_user_id']);
        $this->assertSame(User::ROLE_ADMIN, $log->context['new_role']);
    }

    public function test_only_super_admin_can_send_email_account_password_reset_links(): void
    {
        Notification::fake();
        $superAdmin = User::factory()->create(['role' => User::ROLE_SUPER_ADMIN]);
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $emailUser = User::factory()->create(['role' => User::ROLE_USER, 'google_id' => null]);

        $this->actingAs($admin)->post(route('admin.users.password-reset', $emailUser))->assertForbidden();
        Notification::assertNothingSent();

        $this->actingAs($superAdmin)->post(route('admin.users.password-reset', $emailUser))
            ->assertRedirect()->assertSessionHas('success');

        Notification::assertSentTo($emailUser, ResetPassword::class);
        $this->assertDatabaseHas('activity_logs', [
            'user_id' => $superAdmin->id,
            'action' => 'Password Reset',
            'module' => 'Users',
        ]);
    }

    public function test_google_accounts_cannot_receive_local_password_reset_links(): void
    {
        Notification::fake();
        $superAdmin = User::factory()->create(['role' => User::ROLE_SUPER_ADMIN]);
        $googleUser = User::factory()->create(['google_id' => 'google-123']);

        $this->actingAs($superAdmin)->post(route('admin.users.password-reset', $googleUser))
            ->assertRedirect(route('admin.users.index'))
            ->assertSessionHasErrors('password_reset');
        Notification::assertNothingSent();

        auth()->logout();
        $this->post(route('password.email'), ['email' => $googleUser->email])->assertSessionHas('status');
        Notification::assertNothingSent();
    }

    public function test_mail_transport_failure_returns_to_users_without_server_error(): void
    {
        $superAdmin = User::factory()->create(['role' => User::ROLE_SUPER_ADMIN]);
        $emailUser = User::factory()->create(['google_id' => null]);
        Password::shouldReceive('sendResetLink')->once()->andThrow(new \RuntimeException('SMTP unavailable'));

        $this->actingAs($superAdmin)->post(route('admin.users.password-reset', $emailUser))
            ->assertRedirect(route('admin.users.index'))
            ->assertSessionHasErrors('password_reset');

        $this->assertDatabaseMissing('activity_logs', ['action' => 'sent password reset email']);
    }
}
