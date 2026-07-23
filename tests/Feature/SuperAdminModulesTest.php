<?php

namespace Tests\Feature;

use App\Models\ActivityLog;
use App\Models\SystemSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SuperAdminModulesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        SystemSetting::clearCache();
    }

    public function test_normal_admin_cannot_access_super_admin_modules(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);

        $this->actingAs($admin)->get(route('admin.activity-logs.index'))->assertForbidden();
        $this->actingAs($admin)->get(route('admin.system-settings.index'))->assertForbidden();
    }

    public function test_super_admin_can_access_both_modules(): void
    {
        $superAdmin = User::factory()->create(['role' => User::ROLE_SUPER_ADMIN]);

        $this->actingAs($superAdmin)->get(route('admin.activity-logs.index'))->assertOk()->assertSee('Activity Logs');
        $this->actingAs($superAdmin)->get(route('admin.system-settings.index'))->assertOk()->assertSee('System Settings');
    }

    public function test_super_admin_can_save_system_settings(): void
    {
        $superAdmin = User::factory()->create(['role' => User::ROLE_SUPER_ADMIN]);

        $response = $this->actingAs($superAdmin)->put(route('admin.system-settings.update'), [
            'website_name' => 'SALA CODE LMS',
            'website_description' => 'Programming education',
            'default_language' => 'en',
            'time_zone' => 'Asia/Phnom_Penh',
            'hero_title' => 'Learn to Code',
            'hero_subtitle' => 'Start today',
            'primary_color' => '#1f6fe5',
            'secondary_color' => '#4f46e5',
            'footer_text' => 'SALA CODE',
            'enable_google_login' => '1',
            'enable_registration' => '1',
            'enable_forgot_password' => '1',
            'maintenance_mode' => '0',
            'debug_mode' => '0',
        ]);

        $response->assertRedirect()->assertSessionHas('success');
        $this->assertDatabaseHas('system_settings', ['key' => 'website_name', 'value' => 'SALA CODE LMS']);
    }

    public function test_activity_logs_can_be_filtered_and_viewed(): void
    {
        $superAdmin = User::factory()->create(['role' => User::ROLE_SUPER_ADMIN]);
        ActivityLog::create([
            'user_id' => $superAdmin->id,
            'user_name' => $superAdmin->name,
            'role' => $superAdmin->role,
            'action' => 'Update',
            'module' => 'Courses',
            'description' => 'Updated a course.',
        ]);
        ActivityLog::create([
            'user_id' => $superAdmin->id,
            'user_name' => $superAdmin->name,
            'role' => $superAdmin->role,
            'action' => 'Delete',
            'module' => 'Videos',
            'description' => 'Deleted a video.',
        ]);

        $this->actingAs($superAdmin)
            ->get(route('admin.activity-logs.index', ['module' => 'Courses']))
            ->assertOk()
            ->assertSee('Updated a course.')
            ->assertDontSee('Deleted a video.');
    }

    public function test_successful_login_is_recorded_automatically(): void
    {
        $user = User::factory()->create(['password' => 'password']);

        $this->post(route('login.post'), ['email' => $user->email, 'password' => 'password']);

        $this->assertDatabaseHas('activity_logs', [
            'user_id' => $user->id,
            'action' => 'Login',
            'module' => 'Authentication',
        ]);
    }

    public function test_clear_all_logs_leaves_the_audit_table_empty(): void
    {
        $superAdmin = User::factory()->create(['role' => User::ROLE_SUPER_ADMIN]);
        ActivityLog::create([
            'user_id' => $superAdmin->id,
            'user_name' => $superAdmin->name,
            'role' => $superAdmin->role,
            'action' => 'Update',
            'module' => 'Courses',
            'description' => 'Updated a course.',
        ]);

        $this->actingAs($superAdmin)->delete(route('admin.activity-logs.clear'))->assertRedirect();

        $this->assertDatabaseCount('activity_logs', 0);
    }
}
