<?php

namespace App\Http\Middleware;

use App\Models\SystemSetting;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpFoundation\Response;

class RequireSystemFeature
{
    public function handle(Request $request, Closure $next, string $feature): Response
    {
        if (Schema::hasTable('system_settings') && ! SystemSetting::value($feature, true)) {
            abort(404);
        }

        return $next($request);
    }
}
