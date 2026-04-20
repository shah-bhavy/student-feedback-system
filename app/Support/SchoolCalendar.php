<?php

namespace App\Support;

use App\Models\AppSetting;
use Carbon\CarbonImmutable;
use Throwable;

class SchoolCalendar
{
    private const OVERRIDE_DATE_KEY = 'school_today_override';

    public static function today(): CarbonImmutable
    {
        $override = AppSetting::query()
            ->where('key', self::OVERRIDE_DATE_KEY)
            ->value('value');

        if (is_string($override) && $override !== '') {
            try {
                return CarbonImmutable::parse($override)->startOfDay();
            } catch (Throwable) {
                // Fall back to system date if a malformed value exists in settings.
            }
        }

        return now()->toImmutable()->startOfDay();
    }

    public static function weekKey(): string
    {
        return self::today()->format('o-W');
    }

    public static function isFriday(): bool
    {
        return self::today()->isFriday();
    }

    public static function overrideDate(): ?string
    {
        $value = AppSetting::query()
            ->where('key', self::OVERRIDE_DATE_KEY)
            ->value('value');

        return is_string($value) && $value !== '' ? $value : null;
    }

    public static function setOverrideDate(string $date): void
    {
        AppSetting::query()->updateOrCreate(
            ['key' => self::OVERRIDE_DATE_KEY],
            ['value' => $date],
        );
    }

    public static function clearOverrideDate(): void
    {
        AppSetting::query()
            ->where('key', self::OVERRIDE_DATE_KEY)
            ->delete();
    }
}
