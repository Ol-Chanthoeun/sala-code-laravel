<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Laravel\Socialite\Facades\Socialite;
use Mockery;
use Tests\TestCase;

class GoogleAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_google_redirect_requires_configuration(): void
    {
        Config::set('services.google.client_id', null);
        Config::set('services.google.client_secret', null);

        $this->get(route('google.redirect'))
            ->assertRedirect(route('login'))
            ->assertSessionHasErrors('google');
    }

    public function test_google_callback_creates_and_logs_in_user(): void
    {
        $provider = Mockery::mock();
        $googleUser = Mockery::mock();

        $googleUser->shouldReceive('getId')->andReturn('google-user-123');
        $googleUser->shouldReceive('getEmail')->andReturn('learner@example.com');
        $googleUser->shouldReceive('getName')->andReturn('Google Learner');
        $googleUser->shouldReceive('getNickname')->andReturn(null);
        $googleUser->shouldReceive('getAvatar')->andReturn('https://example.com/avatar.png');

        $provider->shouldReceive('redirectUrl')
            ->once()
            ->with(route('google.callback'))
            ->andReturnSelf();
        $provider->shouldReceive('user')->once()->andReturn($googleUser);
        Socialite::shouldReceive('driver')->with('google')->once()->andReturn($provider);

        $this->get(route('google.callback'))
            ->assertRedirect(route('profile.show'));

        $user = User::where('email', 'learner@example.com')->firstOrFail();

        $this->assertAuthenticatedAs($user);
        $this->assertSame('google-user-123', $user->google_id);
        $this->assertSame(User::ROLE_USER, $user->role);
        $this->assertNotNull($user->email_verified_at);
        $this->assertNotNull($user->last_login_at);
    }
}
