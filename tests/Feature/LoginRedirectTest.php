<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginRedirectTest extends TestCase
{
    use RefreshDatabase;

    public function test_normal_user_login_without_intended_url_redirects_home(): void
    {
        $user = User::factory()->create(['password' => 'Password123']);

        $this->post(route('login.post'), [
            'email' => $user->email,
            'password' => 'Password123',
        ])->assertRedirect(route('home'));
    }

    public function test_normal_user_login_uses_session_intended_url(): void
    {
        $user = User::factory()->create(['password' => 'Password123']);
        $intendedUrl = route('profile.show');

        $this->withSession(['url.intended' => $intendedUrl])
            ->post(route('login.post'), [
                'email' => $user->email,
                'password' => 'Password123',
            ])->assertRedirect($intendedUrl);
    }

    public function test_admin_login_always_redirects_to_admin_dashboard(): void
    {
        $admin = User::factory()->create([
            'password' => 'Password123',
            'role' => User::ROLE_ADMIN,
        ]);

        $this->withSession(['url.intended' => route('profile.show')])
            ->post(route('login.post'), [
                'email' => $admin->email,
                'password' => 'Password123',
            ])->assertRedirect(route('admin.dashboard'));
    }

    public function test_super_admin_login_always_redirects_to_admin_dashboard(): void
    {
        $superAdmin = User::factory()->create([
            'password' => 'Password123',
            'role' => User::ROLE_SUPER_ADMIN,
        ]);

        $this->withSession(['url.intended' => route('profile.show')])
            ->post(route('login.post'), [
                'email' => $superAdmin->email,
                'password' => 'Password123',
            ])->assertRedirect(route('admin.dashboard'));
    }
}
