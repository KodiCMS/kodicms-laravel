<?php

namespace KodiCMS\Support\Helpers;

use DateTimeZone;
use Carbon\Carbon;

/**
 * Class Date
 * TODO: убрать статику. Greabock 20.05.2015.
 */
class Date
{
    const YEAR = 525600;
    const MONTH = 43200;
    const WEEK = 10080;
    const DAY = 1440;
    const HOUR = 60;
    const MINUTE = 1;

    /**
     * @param int|string|Carbon $date
     * @param string                $format
     *
     * @return string
     */
    public static function format($date = null, $format = null)
    {
        if ($format === null) {
            $format = config('cms.date_format');
        }

        if ($date instanceof Carbon) {
            return $date->format($format);
        } elseif (! is_numeric($date)) {
            $date = strtotime($date);
        }

        if (empty($date)) {
            return trans('cms::core.label.date_never');
        }

        return date($format, $date);
    }

    /**
     * @return array
     */
    public static function getTimezones()
    {
        $zones = [];

        foreach (DateTimeZone::listIdentifiers() as $zone) {
            $zones[$zone] = $zone;
        }

        return $zones;
    }

    /**
     * @return array
     */
    public static function getFormats()
    {
        $dateFormats = config('cms.date_format_list', []);
        $dateFormats = array_combine($dateFormats, $dateFormats);

        foreach ($dateFormats as $format => $value) {
            $dateFormats[$format] = Carbon::now()->format($format);
        }

        return $dateFormats;
    }
}
