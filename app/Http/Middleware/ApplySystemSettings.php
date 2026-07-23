<?php

namespace App\Http\Middleware;

use App\Models\SystemSetting;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class ApplySystemSettings
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! Schema::hasTable('system_settings')) {
            View::share('systemSettings', SystemSetting::defaultValues());
            return $next($request);
        }

        $settings = SystemSetting::values();
        config([
            'app.name' => $settings['website_name'],
            'app.locale' => $settings['default_language'],
            'app.timezone' => $settings['time_zone'],
            'app.debug' => $settings['debug_mode'],
        ]);
        date_default_timezone_set($settings['time_zone']);
        app()->setLocale($settings['default_language']);
        View::share('systemSettings', $settings);

        $allowedDuringMaintenance = $request->is('admin*', 'login', 'logout') || $request->user()?->isSuperAdmin();
        if ($settings['maintenance_mode'] && ! $allowedDuringMaintenance) {
            abort(503, 'The website is currently under maintenance.');
        }

        return $next($request);
    }
}
