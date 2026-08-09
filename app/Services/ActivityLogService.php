<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class ActivityLogService
{
    private const SENSITIVE = ['password','password_confirmation','remember_token','token','access_token','refresh_token','secret','client_secret'];

    public static function log(Request $request, string $action, string $module, Model|string|null $target=null, ?string $description=null, array $oldValues=[], array $newValues=[], ?User $actor=null, ?string $severity=null): ActivityLog
    {
        $actor ??= $request->user();
        [$targetType,$targetId,$targetName] = self::target($target);
        $severity ??= self::severity($request, $action);
        $request->attributes->set('activity_logged', true);

        return ActivityLog::create([
            'user_id'=>$actor?->id,
            'user_name'=>$actor?->name ?? $request->input('email') ?? 'Guest',
            'user_email'=>$actor?->email ?? $request->input('email'),
            'role'=>$actor?->role ?? 'guest',
            'action'=>$action,
            'module'=>$module,
            'target_type'=>$targetType,
            'target_id'=>$targetId,
            'target_name'=>$targetName,
            'description'=>$description ?: trim("{$action} {$targetName}"),
            'old_values'=>self::sanitize($oldValues) ?: null,
            'new_values'=>self::sanitize($newValues) ?: null,
            'ip_address'=>$request->ip(),
            'user_agent'=>$request->userAgent(),
            'browser'=>self::browser($request->userAgent()),
            'device'=>self::device($request->userAgent()),
            'severity'=>$severity,
            'method'=>$request->method(),
            'route_name'=>$request->route()?->getName(),
            'context'=>[],
        ]);
    }

    private static function target(Model|string|null $target): array
    {
        if ($target instanceof Model) {
            $name = $target instanceof User
                ? $target->email
                : ($target->getAttribute('title') ?? $target->getAttribute('name') ?? $target->getAttribute('email') ?? class_basename($target).' #'.$target->getKey());
            return [$target::class,$target->getKey(),(string)$name];
        }
        return [null,null,$target];
    }

    public static function sanitize(array $values): array
    {
        return collect($values)->reject(fn ($value,$key)=>in_array(strtolower((string)$key),self::SENSITIVE,true) || str_contains(strtolower((string)$key),'token') || str_contains(strtolower((string)$key),'password') || str_contains(strtolower((string)$key),'secret'))->map(fn($value)=>is_array($value)?self::sanitize($value):$value)->all();
    }

    private static function severity(Request $request, string $action): string
    {
        $actionLower=strtolower($action);
        if (str_contains($actionLower,'failed login') || str_contains($actionLower,'permission denied')) {
            $recent=ActivityLog::where('ip_address',$request->ip())->where('created_at','>=',now()->subMinutes(15))->where(fn($q)=>$q->where('action','like','%Failed Login%')->orWhere('action','like','%Permission Denied%'))->count();
            return $recent >= 3 ? 'suspicious' : 'warning';
        }
        if (str_contains($actionLower,'delete')) {
            $recent=ActivityLog::where('ip_address',$request->ip())->where('created_at','>=',now()->subMinutes(10))->where('action','like','%delete%')->count();
            return $recent >= 4 ? 'suspicious' : 'warning';
        }
        if (str_contains($actionLower,'role') && str_contains($actionLower,'chang')) {
            $recent=ActivityLog::where('user_id',$request->user()?->id)->where('created_at','>=',now()->subMinutes(15))->where('action','like','%role%')->count();
            return $recent >= 3 ? 'suspicious' : 'warning';
        }
        if (str_contains($actionLower, 'deactiv') || str_contains($actionLower, 'blocked')) return 'warning';
        return 'normal';
    }

    public static function browser(?string $agent): ?string
    {
        if (!$agent) return null;
        return match(true){str_contains($agent,'Edg/')=>'Microsoft Edge',str_contains($agent,'OPR/')=>'Opera',str_contains($agent,'Chrome/')=>'Google Chrome',str_contains($agent,'Firefox/')=>'Mozilla Firefox',str_contains($agent,'Safari/')=>'Safari',default=>'Other'};
    }

    public static function device(?string $agent): ?string
    {
        if (!$agent) return null;
        $os=match(true){str_contains($agent,'Windows')=>'Windows',str_contains($agent,'Android')=>'Android',str_contains($agent,'iPhone'),str_contains($agent,'iPad')=>'iOS',str_contains($agent,'Macintosh')=>'macOS',str_contains($agent,'Linux')=>'Linux',default=>null};
        if (!$os) return 'Unknown';
        $kind=match(true){str_contains($agent,'Mobile'),str_contains($agent,'Android'),str_contains($agent,'iPhone')=>'Mobile',str_contains($agent,'iPad'),str_contains($agent,'Tablet')=>'Tablet',default=>'Desktop'};
        return "{$os} {$kind}";
    }
}
