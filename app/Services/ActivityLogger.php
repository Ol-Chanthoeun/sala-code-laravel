<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class ActivityLogger
{
    public static function record(Request $request, ?User $user = null, mixed $target=null, array $oldValues=[], array $newValues=[]): void
    {
        $routeName = $request->route()?->getName() ?? 'unknown';
        $action = self::action($request, $routeName, $oldValues, $newValues);
        $module = self::module($routeName);

        ActivityLogService::log($request,$action,$module,$target,self::description($action,$module,$user,$target,$oldValues,$newValues),$oldValues,$newValues,$user);
    }

    private static function action(Request $request, string $routeName, array $oldValues, array $newValues): string
    {
        if (array_key_exists('status', $newValues) && ($oldValues['status'] ?? null) !== $newValues['status']) {
            return in_array(strtolower((string) $newValues['status']), ['published', 'active'], true) ? 'Publish' : 'Unpublish';
        }
        if (array_key_exists('thumbnail', $newValues) && str_starts_with($routeName, 'admin.video-playlists.')) return 'Change Thumbnail';
        if (array_intersect(['order', 'order_number', 'sort_order'], array_keys($newValues))) return 'Reorder';
        if (str_starts_with($routeName, 'admin.quiz-questions.') && array_intersect(['correct_answer', 'correct_choice_id', 'is_correct'], array_keys($newValues))) return 'Change Correct Answer';

        return match (true) {
            $routeName === 'login.post', $routeName === 'google.callback' => 'Login',
            $routeName === 'logout' => 'Logout',
            $routeName === 'register.post' => 'Register',
            $routeName === 'password.email', $routeName === 'password.update' => 'Password Reset',
            $routeName === 'profile.update' => 'Change Profile',
            $routeName === 'profile.password.update' => 'Change Password',
            $request->isMethod('delete') => 'Delete',
            $request->isMethod('post') => 'Create',
            in_array($request->method(), ['PUT', 'PATCH'], true) => 'Update',
            default => 'View',
        };
    }

    private static function module(string $routeName): string
    {
        return match (true) {
            str_starts_with($routeName, 'admin.admins.') => 'Admins',
            str_starts_with($routeName, 'admin.users.') => 'Users',
            str_starts_with($routeName, 'admin.courses.') => 'Courses',
            str_starts_with($routeName, 'admin.sections.') => 'Sections',
            str_starts_with($routeName, 'admin.lessons.') => 'Lessons',
            str_starts_with($routeName, 'admin.examples.') => 'Code Examples',
            str_starts_with($routeName, 'admin.videos.') => 'Videos',
            str_starts_with($routeName, 'admin.video-playlists.') => 'Video Playlists',
            str_starts_with($routeName, 'admin.quiz-questions.') => 'Quiz Questions',
            str_starts_with($routeName, 'admin.quiz-categories.') => 'Quiz Categories',
            str_starts_with($routeName, 'admin.programming-languages.') => 'Quiz Languages',
            str_starts_with($routeName, 'admin.quizzes.'), str_starts_with($routeName, 'admin.quiz-') => 'Quizzes',
            str_starts_with($routeName, 'admin.contacts'), str_starts_with($routeName, 'contact.') => 'Contact Messages',
            str_starts_with($routeName, 'admin.activity-logs') => 'Activity Logs',
            str_starts_with($routeName, 'admin.system-settings') => 'System Settings',
            str_starts_with($routeName, 'profile.') => 'Profile',
            str_starts_with($routeName, 'password.') => 'Authentication',
            in_array($routeName, ['login.post', 'logout', 'register.post', 'google.callback'], true) => 'Authentication',
            default => 'Application',
        };
    }

    private static function description(string $action, string $module, ?User $user, mixed $target, array $oldValues, array $newValues): string
    {
        $name = $user?->name ?? 'Guest';
        $label = $target instanceof Model
            ? ($target instanceof User ? $target->email : ($target->getAttribute('title') ?? $target->getAttribute('name') ?? class_basename($target).' #'.$target->getKey()))
            : ($target ?: strtolower($module));
        $noun = strtolower(rtrim($module, 's'));

        if ($action === 'Change Correct Answer') return "{$name} changed the correct answer for {$noun} '{$label}'.";
        if ($action === 'Change Thumbnail') return "{$name} changed the thumbnail for {$noun} '{$label}'.";
        if ($action === 'Reorder') return "{$name} reordered {$noun} '{$label}'.";
        if ($action === 'Publish') return "{$name} published {$noun} '{$label}'.";
        if ($action === 'Unpublish') return "{$name} unpublished {$noun} '{$label}'.";

        $verb = ['Create'=>'created','Update'=>'updated','Delete'=>'deleted','View'=>'viewed'][$action] ?? strtolower($action);
        return "{$name} {$verb} {$noun} '{$label}'.";
    }

}
