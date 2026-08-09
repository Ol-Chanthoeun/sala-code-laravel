<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\Request;

class UserSecurityLogger
{
    public static function record(Request $request, User $actor, User $target, string $action, array $context = []): void
    {
        $oldValues = collect($context)->filter(fn($value,$key)=>str_starts_with($key,'previous_'))->mapWithKeys(fn($value,$key)=>[str($key)->after('previous_')->toString()=>$value])->all();
        $newValues = collect($context)->filter(fn($value,$key)=>str_starts_with($key,'new_'))->mapWithKeys(fn($value,$key)=>[str($key)->after('new_')->toString()=>$value])->all();
        $rawAction = strtolower($action);
        $canonical = match (true) {
            str_contains($rawAction, 'role') => 'Role Change',
            str_contains($rawAction, 'password reset') => 'Password Reset',
            str_contains($rawAction, 'deactiv') => 'Deactivate',
            str_contains($rawAction, 'activat') => 'Activate',
            str_contains($rawAction, 'delet') => 'Delete',
            str_contains($rawAction, 'creat') => 'Create',
            default => 'Update',
        };
        $module = str_contains($rawAction, 'admin') ? 'Admins' : 'Users';
        $description = match ($canonical) {
            'Role Change' => "{$actor->name} changed {$target->name}'s role from ".str($oldValues['role'] ?? '-')->headline()." to ".str($newValues['role'] ?? '-')->headline().'.',
            'Password Reset' => "{$actor->name} sent a password reset link to {$target->name} ({$target->email}).",
            'Activate' => "{$actor->name} activated {$target->name} ({$target->email}).",
            'Deactivate' => "{$actor->name} deactivated {$target->name} ({$target->email}).",
            'Delete' => "{$actor->name} deleted {$target->name} ({$target->email}).",
            'Create' => "{$actor->name} created {$target->name} ({$target->email}).",
            default => "{$actor->name} updated {$target->name} ({$target->email}).",
        };
        $log = ActivityLogService::log($request,$canonical,$module,$target,$description,$oldValues,$newValues,$actor);
        $log->forceFill(['context'=>['target_user_id'=>$target->id,'target_name'=>$target->name,'target_email'=>$target->email,...$context]])->saveQuietly();
    }
}
