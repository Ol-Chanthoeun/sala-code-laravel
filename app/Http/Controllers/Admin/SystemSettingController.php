<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\File;
use Throwable;

class SystemSettingController extends Controller
{
    public function index(): View
    {
        return view('admin.system-settings.index', ['settings' => SystemSetting::values()]);
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'website_name' => ['required', 'string', 'max:100'],
            'website_description' => ['nullable', 'string', 'max:1000'],
            'default_language' => ['required', 'in:en,km'],
            'time_zone' => ['required', 'timezone'],
            'hero_title' => ['nullable', 'string', 'max:255'],
            'hero_subtitle' => ['nullable', 'string', 'max:1000'],
            'contact_email' => ['nullable', 'email', 'max:255'],
            'contact_phone' => ['nullable', 'string', 'max:50'],
            'contact_address' => ['nullable', 'string', 'max:1000'],
            'facebook_url' => ['nullable', 'url', 'max:500'],
            'telegram_url' => ['nullable', 'url', 'max:500'],
            'youtube_url' => ['nullable', 'url', 'max:500'],
            'github_url' => ['nullable', 'url', 'max:500'],
            'primary_color' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'secondary_color' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'footer_text' => ['nullable', 'string', 'max:255'],
            'enable_google_login' => ['nullable', 'boolean'],
            'enable_registration' => ['nullable', 'boolean'],
            'enable_forgot_password' => ['nullable', 'boolean'],
            'maintenance_mode' => ['nullable', 'boolean'],
            'debug_mode' => ['nullable', 'boolean'],
            'website_logo' => ['nullable', File::types(['png', 'jpg', 'jpeg', 'svg', 'webp'])->max(2 * 1024)],
            'favicon' => ['nullable', File::types(['png', 'jpg', 'jpeg', 'svg', 'webp', 'ico'])->max(1024)],
            'hero_image' => ['nullable', File::types(['png', 'jpg', 'jpeg', 'webp'])->max(4 * 1024)],
        ]);

        $newUploads = [];
        $oldUploads = [];

        try {
            DB::transaction(function () use ($request, $data, &$newUploads, &$oldUploads): void {
                foreach (SystemSetting::DEFAULTS as $key => [$group, $default, $type]) {
                    if ($type === 'image') {
                        if (! $request->hasFile($key)) {
                            continue;
                        }

                        $old = SystemSetting::query()->where('key', $key)->value('value');
                        $data[$key] = $request->file($key)->store('system-settings', 'public');
                        $newUploads[] = $data[$key];
                        if ($old) {
                            $oldUploads[] = $old;
                        }
                    }

                    $value = $type === 'boolean' ? (string) $request->boolean($key) : ($data[$key] ?? $default);
                    SystemSetting::updateOrCreate(['key' => $key], ['group' => $group, 'value' => $value, 'type' => $type]);
                }
            });
        } catch (Throwable $exception) {
            Storage::disk('public')->delete($newUploads);
            throw $exception;
        }

        Storage::disk('public')->delete($oldUploads);

        SystemSetting::clearCache();

        return back()->with('success', 'System settings saved successfully.');
    }

    public function reset(): RedirectResponse
    {
        $images = SystemSetting::query()->where('type', 'image')->pluck('value')->filter()->all();
        DB::transaction(fn () => SystemSetting::query()->delete());
        Storage::disk('public')->delete($images);
        SystemSetting::clearCache();

        return back()->with('success', 'System settings reset to defaults.');
    }
}
