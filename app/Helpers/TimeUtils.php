<?php

namespace App\Helpers;

use Carbon\CarbonInterval;

class TimeUtils
{
    public static function formatTimestamp($timestamp): string
    {
        return date('d-m-Y H:i:s', $timestamp / 1000);
    }

    public static function millisToReadableFormat($millis): string {
        try {
            return CarbonInterval::millisecond($millis)->cascade()->forHumans();
        } catch (\Exception $ex) {
            return $ex->getMessage();
        }
    }
}
