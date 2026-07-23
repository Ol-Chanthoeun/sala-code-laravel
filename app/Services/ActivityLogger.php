<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;

class ActivityLogger
{
    public static function record(Request $request, ?User $user = null): void
    {
        $routeName = $request->route()?->getName() ?? 'unknown';
        $action = self::action($request, $routeName);
        $module = self::module($routeName);

        ActivityLog::create([
            'user_id' => $user?->id,
            'user_name' => $user?->name ?? $request->input('email') ?? 'Guest',
            'role' => $user?->role ?? 'guest',
            'action' => $action,
            'module' => $module,
            'description' => self::description($action, $module, $user),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'browser' => self::browser($request->userAgent()),
            'method' => $request->method(),
            'route_name' => $routeName,
            'context' => collect($request->route()?->parameters() ?? [])->map(
                fn (mixed $value) => $value instanceof \Illuminate\Database\Eloquent\Model ? $value->getKey() : $value
            )->all(),
        ]);
    }

    private static function action(Request $request, string $routeName): string
    {
        return match (true) {
            $routeName === 'login.post', $routeName === 'google.callback' => 'Login',
            $routeName === 'logout' => 'Logout',
            $routeName === 'register.post' => 'Register',
            $routeName === 'password.email', $routeName === 'password.update' => 'Password Reset',
            $routeName === 'profile.update' => 'Change Profile',
            $routeName === 'profile.password.update' => 'Change Password',
            $request->hasFile('avatar'), $request->hasFile('logo'), $request->hasFile('image'), $request->hasFile('thumbnail') => 'Upload Image',
            $request->input('status') === 'published' => 'Publish',
            in_array($request->input('status'), ['draft', 'archived'], true) => 'Unpublish',
            $request->isMethod('delete') => 'Delete',
            $request->isMethod('post') => 'Create',
            in_array($request->method(), ['PUT', 'PATCH'], true) => 'Update',
            default => 'View',
        };
    }

    private static function module(string $routeName): string
    {
        return match (true) {
            str_starts_with($routeName, 'admin.users.'), str_starts_with($routeName, 'admin.admins.') => 'Users',
            str_starts_with($routeName, 'admin.courses.'), str_starts_with($routeName, 'admin.sections.') => 'Courses',
            str_starts_with($routeName, 'admin.lessons.'), str_starts_with($routeName, 'admin.examples.') => 'Lessons',
            str_starts_with($routeName, 'admin.videos.') => 'Videos',
            str_starts_with($routeName, 'admin.quizzes.'), str_starts_with($routeName, 'admin.quiz-'), str_starts_with($routeName, 'admin.programming-languages.') => 'Quizzes',
            str_starts_with($routeName, 'admin.contacts'), str_starts_with($routeName, 'contact.') => 'Contact Messages',
            str_starts_with($routeName, 'admin.activity-logs') => 'Activity Logs',
            str_starts_with($routeName, 'admin.system-settings') => 'System Settings',
            str_starts_with($routeName, 'profile.') => 'Profile',
            str_starts_with($routeName, 'password.') => 'Authentication',
            in_array($routeName, ['login.post', 'logout', 'register.post', 'google.callback'], true) => 'Authentication',
            default => 'Application',
        };
    }

    private static function description(string $action, string $module, ?User $user): string
    {
        $name = $user?->name ?? 'Guest';

        return "{$name} performed {$action} in {$module}.";
    }

    private static function browser(?string $agent): string
    {
        if (! $agent) {
            return 'Unknown';
        }

        return match (true) {
            str_contains($agent, 'Edg/') => 'Microsoft Edge',
            str_contains($agent, 'OPR/'), str_contains($agent, 'Opera') => 'Opera',
            str_contains($agent, 'Chrome/') => 'Google Chrome',
            str_contains($agent, 'Firefox/') => 'Mozilla Firefox',
            str_contains($agent, 'Safari/') => 'Safari',
            default => 'Other',
        };
    }
}
