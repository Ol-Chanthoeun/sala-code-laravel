<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SystemSetting extends Model
{
    protected $fillable = ['group', 'key', 'value', 'type'];

    public const DEFAULTS = [
        'website_name' => ['general', 'Sala Code', 'string'],
        'website_logo' => ['general', null, 'image'],
        'favicon' => ['general', null, 'image'],
        'website_description' => ['general', null, 'text'],
        'default_language' => ['general', 'en', 'string'],
        'time_zone' => ['general', 'Asia/Phnom_Penh', 'string'],
        'hero_title' => ['homepage', null, 'string'],
        'hero_subtitle' => ['homepage', null, 'text'],
        'hero_image' => ['homepage', null, 'image'],
        'contact_email' => ['contact', null, 'string'],
        'contact_phone' => ['contact', null, 'string'],
        'contact_address' => ['contact', null, 'text'],
        'facebook_url' => ['contact', null, 'string'],
        'telegram_url' => ['contact', null, 'string'],
        'youtube_url' => ['contact', null, 'string'],
        'github_url' => ['contact', null, 'string'],
        'enable_google_login' => ['authentication', true, 'boolean'],
        'enable_registration' => ['authentication', true, 'boolean'],
        'enable_forgot_password' => ['authentication', true, 'boolean'],
        'primary_color' => ['appearance', '#1f6fe5', 'color'],
        'secondary_color' => ['appearance', '#4f46e5', 'color'],
        'footer_text' => ['appearance', null, 'string'],
        'maintenance_mode' => ['admin', false, 'boolean'],
        'debug_mode' => ['admin', false, 'boolean'],
    ];

    public static function values(): array
    {
        return Cache::remember('system_settings.values', 3600, function (): array {
            $stored = static::query()->pluck('value', 'key')->all();
            $values = static::defaultValues();

            foreach (self::DEFAULTS as $key => [, $default, $type]) {
                $value = array_key_exists($key, $stored) ? $stored[$key] : $default;
                $values[$key] = $type === 'boolean' ? filter_var($value, FILTER_VALIDATE_BOOLEAN) : $value;
            }

            return $values;
        });
    }

    public static function defaultValues(): array
    {
        $values = [];

        foreach (self::DEFAULTS as $key => [, $default]) {
            $values[$key] = $default;
        }

        return $values;
    }

    public static function value(string $key, mixed $fallback = null): mixed
    {
        return static::values()[$key] ?? $fallback;
    }

    public static function clearCache(): void
    {
        Cache::forget('system_settings.values');
    }
}
