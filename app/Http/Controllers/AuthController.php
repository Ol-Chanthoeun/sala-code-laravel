<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Models\User;
use App\Services\ActivityLogService;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Throwable;

class AuthController extends Controller
{
    public function showRegister(): View
    {
        return view('auth.register');
    }

    public function register(RegisterRequest $request): RedirectResponse
    {
        $user = User::create([
            'name' => $request->validated('name'),
            'email' => $request->validated('email'),
            'password' => $request->validated('password'),
            'role' => User::ROLE_USER,
            'status' => User::STATUS_ACTIVE,
        ]);

        Auth::login($user);

        $request->session()->regenerate();
        ActivityLogService::log($request,'User Created','Authentication',$user,'New email account registered.',[],['role'=>$user->role,'status'=>$user->status],$user);

        return $this->redirectAfterAuthentication($request, $user)
            ->with('success', 'Welcome to Sala Code. Your account has been created.');
    }

    public function showLogin(): View
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request): RedirectResponse
    {
        $user = User::where('email', $request->validated('email'))->first();

        if ($user && ! $user->isActive()) {
            ActivityLogService::log($request,'Account Blocked','Security',$user,'Disabled account was denied email/password login.',[],[],null,'warning');
            return back()->withErrors([
                'email' => 'Your account has been disabled. Please contact support.',
            ])->onlyInput('email');
        }

        $credentials = [
            'email' => $request->validated('email'),
            'password' => $request->validated('password'),
            'status' => User::STATUS_ACTIVE,
        ];

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $request->user()->forceFill([
                'last_login_at' => now(),
            ])->save();
            ActivityLogService::log($request,'Login Success','Authentication',$request->user(),'Email/password login succeeded.');

            return $this->redirectAfterAuthentication($request, $request->user());
        }

        ActivityLogService::log($request,'Failed Login','Security',$request->validated('email'),'Email/password login failed.',[],[],null);
        return back()->withErrors([
            'email' => 'The email or password is incorrect.',
        ])->onlyInput('email');
    }

    public function logout(Request $request): RedirectResponse
    {
        ActivityLogService::log($request,'Logout','Authentication',$request->user(),'User logged out securely.');
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'You have been logged out securely.');
    }

    public function showForgotPasswordForm(): View
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(ForgotPasswordRequest $request): RedirectResponse
    {
        $user = User::where('email', $request->validated('email'))->first();

        if ($user?->usesGoogleAuthentication()) {
            ActivityLogService::log($request,'Password Reset Requested','Authentication',$user,'Google account requested local password recovery; no reset link was issued.');
            return back()->with('status', __(Password::RESET_LINK_SENT));
        }

        $status = Password::sendResetLink($request->only('email'));

        if ($status === Password::RESET_LINK_SENT) {
            ActivityLogService::log($request,'Password Reset Requested','Authentication',$user ?: $request->validated('email'),'Secure password reset link requested.');
        }

        return $status === Password::RESET_LINK_SENT
            ? back()->with('status', __($status))
            : back()->withErrors(['email' => __($status)])->onlyInput('email');
    }

    public function showResetPasswordForm(Request $request, string $token): View
    {
        return view('auth.reset-password', [
            'email' => $request->query('email'),
            'token' => $token,
        ]);
    }

    public function resetPassword(ResetPasswordRequest $request): RedirectResponse
    {
        $user = User::where('email', $request->validated('email'))->first();

        if ($user?->usesGoogleAuthentication()) {
            return back()->withErrors([
                'email' => 'Google accounts must use Google account recovery.',
            ])->onlyInput('email');
        }

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password): void {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            ActivityLogService::log($request,'Password Changed','Authentication',$user,'Password changed using a secure reset token.');
        }

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('success', __($status))
            : back()->withErrors(['email' => __($status)])->onlyInput('email');
    }

    public function redirectToGoogle(): RedirectResponse
    {
        if (! config('services.google.client_id') || ! config('services.google.client_secret')) {
            return redirect()
                ->route('login')
                ->withErrors([
                    'google' => 'Google login is not configured. Please set GOOGLE_CLIENT_ID and GOOGLE_CLIENT_SECRET.',
                ]);
        }

        return Socialite::driver('google')
            ->redirectUrl($this->googleCallbackUrl())
            ->redirect();
    }

    public function handleGoogleCallback(Request $request): RedirectResponse
    {
        try {
            $googleUser = Socialite::driver('google')
                ->redirectUrl($this->googleCallbackUrl())
                ->user();
        } catch (Throwable) {
            return redirect()
                ->route('login')
                ->withErrors([
                    'google' => 'Google login failed. Please try again.',
                ]);
        }

        if (! $googleUser->getEmail()) {
            return redirect()
                ->route('login')
                ->withErrors([
                    'google' => 'Google did not return an email address for this account.',
                ]);
        }

        $user = User::where('google_id', $googleUser->getId())->first()
            ?? User::where('email', $googleUser->getEmail())->first();

        if ($user && ! $user->isActive()) {
            ActivityLogService::log($request,'Account Blocked','Security',$user,'Disabled account was denied Google OAuth login.',[],[],null,'warning');
            return redirect()
                ->route('login')
                ->withErrors([
                    'google' => 'Your account has been disabled. Please contact support.',
                ]);
        }

        if (! $user) {
            $user = User::create([
                'name' => $googleUser->getName() ?: $googleUser->getNickname() ?: Str::before($googleUser->getEmail(), '@'),
                'email' => $googleUser->getEmail(),
                'password' => Str::random(40),
                'role' => User::ROLE_USER,
                'status' => User::STATUS_ACTIVE,
                'google_id' => $googleUser->getId(),
                'avatar' => $googleUser->getAvatar(),
            ]);
        }

        $user->forceFill([
            'google_id' => $user->google_id ?: $googleUser->getId(),
            'avatar' => $googleUser->getAvatar() ?: $user->avatar,
            'email_verified_at' => $user->email_verified_at ?: now(),
            'last_login_at' => now(),
        ])->save();

        Auth::login($user, remember: true);

        $request->session()->regenerate();
        ActivityLogService::log($request,'Google Login','Authentication',$user,'Google OAuth login succeeded.');

        return $this->redirectAfterAuthentication($request, $user);
    }

    private function googleCallbackUrl(): string
    {
        return route('google.callback');
    }

    private function redirectAfterAuthentication(Request $request, User $user): RedirectResponse
    {
        if ($user->isAdmin() || $user->isSuperAdmin()) {
            $request->session()->forget('url.intended');

            return redirect()->route('admin.dashboard');
        }

        return redirect()->intended(route('home'));
    }
}
