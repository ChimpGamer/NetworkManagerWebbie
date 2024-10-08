<?php

namespace App\Helpers;

use Carbon\Carbon;
use Carbon\CarbonInterval;

class TimeUtils
{
    public static function formatTimestamp(float $timestamp): string
    {
        return Carbon::createFromTimestampMs($timestamp, config('app.timezone', 'UTC'))->toDateTimeString();
    }

    public static function millisToReadableFormat($millis): string
    {
        try {
            return CarbonInterval::millisecond($millis)->cascade()->forHumans();
        } catch (\Exception $ex) {
            return $ex->getMessage();
        }
    }
}
