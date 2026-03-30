<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = ['key', 'value', 'type', 'group', 'label', 'description'];

    /** Cache TTL in seconds (24 hours). */
    protected const CACHE_TTL = 86400;

    /** Get a setting value by key, returning $default when not found. */
    public static function get(string $key, mixed $default = null): mixed
    {
        $all = self::allCached();

        if (! array_key_exists($key, $all)) {
            return $default;
        }

        $setting = $all[$key];

        return self::castValue($setting['value'], $setting['type']);
    }

    /** Persist a setting value and refresh the cache. */
    public static function set(string $key, mixed $value): void
    {
        self::where('key', $key)->update(['value' => $value]);
        self::clearCache();
    }

    /** Return all settings keyed by group → [ ['label'=>…, 'key'=>…, …] ]. */
    public static function groupedAll(): array
    {
        $all = self::allCached();
        $grouped = [];

        foreach ($all as $key => $setting) {
            $grouped[$setting['group']][] = array_merge($setting, ['key' => $key]);
        }

        return $grouped;
    }

    /** Flush the settings cache. */
    public static function clearCache(): void
    {
        Cache::forget('settings.all');
    }

    // ─── Internals ────────────────────────────────────────────────────────────

    /** Load all settings from cache or database. */
    protected static function allCached(): array
    {
        return Cache::remember('settings.all', self::CACHE_TTL, function () {
            return self::all()
                ->keyBy('key')
                ->map(fn ($s) => [
                    'value'       => $s->value,
                    'type'        => $s->type,
                    'group'       => $s->group,
                    'label'       => $s->label,
                    'description' => $s->description,
                ])
                ->toArray();
        });
    }

    protected static function castValue(mixed $value, string $type): mixed
    {
        if ($value === null) {
            return $value;
        }

        return match ($type) {
            'integer' => (int) $value,
            'decimal' => (float) $value,
            'boolean' => filter_var($value, FILTER_VALIDATE_BOOLEAN),
            default   => (string) $value,
        };
    }
}
