<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Services\ActivityLogger;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class LogUserActivity
{
    public function handle(Request $request, Closure $next): Response
    {
        /** @var User|null $userBefore */
        $userBefore = Auth::user();
        $response = $next($request);

        $isLogMaintenance = str_starts_with($request->route()?->getName() ?? '', 'admin.activity-logs.');

        $shouldLog = ! $request->isMethod('get') || $request->route()?->getName() === 'google.callback';

        if ($shouldLog && ! $isLogMaintenance && $response->getStatusCode() < 400 && Schema::hasTable('activity_logs')) {
            try {
                /** @var User|null $actor */
                $actor = $userBefore ?? Auth::user();
                ActivityLogger::record($request, $actor);
            } catch (Throwable $exception) {
                Log::warning('Activity logging failed.', ['error' => $exception->getMessage()]);
            }
        }

        return $response;
    }
}
