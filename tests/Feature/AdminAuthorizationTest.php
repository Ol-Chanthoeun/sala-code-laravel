<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
}
