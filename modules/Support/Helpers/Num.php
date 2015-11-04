<?php

namespace KodiCMS\Support\Helpers;

use KodiCMS\CMS\Exceptions\Exception;

class Num
{
    const ROUND_HALF_UP = 1;
    const ROUND_HALF_DOWN = 2;
    const ROUND_HALF_EVEN = 3;
    const ROUND_HALF_ODD = 4;

    /**
     * @var  array  Valid byte units => power of 2 that defines the unit's size
     */
    public static $byte_units = [
        'B'   => 0,
        'K'   => 10,
        'Ki'  => 10,
        'KB'  => 10,
        'KiB' => 10,
        'M'   => 20,
        'Mi'  => 20,
        'MB'  => 20,
        'MiB' => 20,
        'G'   => 30,
        'Gi'  => 30,
        'GB'  => 30,
        'GiB' => 30,
        'T'   => 40,
        'Ti'  => 40,
        'TB'  => 40,
        'TiB' => 40,
        'P'   => 50,
        'Pi'  => 50,
        'PB'  => 50,
        'PiB' => 50,
        'E'   => 60,
        'Ei'  => 60,
        'EB'  => 60,
        'EiB' => 60,
        'Z'   => 70,
        'Zi'  => 70,
        'ZB'  => 70,
        'ZiB' => 70,
        'Y'   => 80,
        'Yi'  => 80,
        'YB'  => 80,
        'YiB' => 80,
    ];

    /**
     * Returns the English ordinal suffix (th, st, nd, etc) of a number.
     *
     *     echo 2, Num::ordinal(2);   // "2nd"
     *     echo 10, Num::ordinal(10); // "10th"
     *     echo 33, Num::ordinal(33); // "33rd"
     *
     * @param   int $number
     *
     * @return  string
     */
    public static function ordinal($number)
    {
        if ($number % 100 > 10 and $number % 100 < 14) {
            return 'th';
        }

        switch ($number % 10) {
            case 1:
                return 'st';
            case 2:
                return 'nd';
            case 3:
                return 'rd';
            default:
                return 'th';
        }
    }

    /**
     * Locale-aware number and monetary formatting.
     *
     *     // In English, "1,200.05"
     *     // In Spanish, "1200,05"
     *     // In Portuguese, "1 200,05"
     *     echo Num::format(1200.05, 2);
     *
     *     // In English, "1,200.05"
     *     // In Spanish, "1.200,05"
     *     // In Portuguese, "1.200.05"
     *     echo Num::format(1200.05, 2, TRUE);
     *
     * @param   float   $number   number to format
     * @param   int $places   decimal places
     * @param   bool $monetary monetary formatting?
     *
     * @return  string
     * @since   3.0.2
     */
    public static function format($number, $places, $monetary = false)
    {
        $info = localeconv();

        if ($monetary) {
            $decimal = $info['mon_decimal_point'];
            $thousands = $info['mon_thousands_sep'];
        } else {
            $decimal = $info['decimal_point'];
            $thousands = $info['thousands_sep'];
        }

        return number_format($number, $places, $decimal, $thousands);
    }

    /**
     * Round a number to a specified precision, using a specified tie breaking technique.
     *
     * @param float   $value     Number to round
     * @param int $precision Desired precision
     * @param int $mode      Tie breaking mode, accepts the PHP_ROUND_HALF_* constants
     * @param bool $native    Set to false to force use of the userland implementation
     *
     * @return float Rounded number
     */
    public static function round($value, $precision = 0, $mode = self::ROUND_HALF_UP, $native = true)
    {
        if (version_compare(PHP_VERSION, '5.3', '>=') and $native) {
            return round($value, $precision, $mode);
        }

        if ($mode === static::ROUND_HALF_UP) {
            return round($value, $precision);
        } else {
            $factor = ($precision === 0) ? 1 : pow(10, $precision);

            switch ($mode) {
                case static::ROUND_HALF_DOWN:
                case static::ROUND_HALF_EVEN:
                case static::ROUND_HALF_ODD:
                    // Check if we have a rounding tie, otherwise we can just call round()
                    if (($value * $factor) - floor($value * $factor) === 0.5) {
                        if ($mode === static::ROUND_HALF_DOWN) {
                            // Round down operation, so we round down unless the value
                            // is -ve because up is down and down is up down there. ;)
                            $up = ($value < 0);
                        } else {
                            // Round up if the integer is odd and the round mode is set to even
                            // or the integer is even and the round mode is set to odd.
                            // Any other instance round down.
                            $up = (! (! (floor($value * $factor) & 1)) === ($mode === static::ROUND_HALF_EVEN));
                        }

                        if ($up) {
                            $value = ceil($value * $factor);
                        } else {
                            $value = floor($value * $factor);
                        }

                        return $value / $factor;
                    } else {
                        return round($value, $precision);
                    }
                    break;
            }
        }
    }

    /**
     * Converts a file size number to a byte value. File sizes are defined in
     * the format: SB, where S is the size (1, 8.5, 300, etc.) and B is the
     * byte unit (K, MiB, GB, etc.). All valid byte units are defined in
     * Num::$byte_units.
     *
     *     echo Num::bytes('200K');  // 204800
     *     echo Num::bytes('5MiB');  // 5242880
     *     echo Num::bytes('1000');  // 1000
     *     echo Num::bytes('2.5GB'); // 2684354560
     *
     * @param   string $bytes file size in SB format
     *
     * @return  float
     */
    public static function bytes($size)
    {
        // Prepare the size
        $size = trim((string) $size);

        // Construct an OR list of byte units for the regex
        $accepted = implode('|', array_keys(static::$byte_units));

        // Construct the regex pattern for verifying the size format
        $pattern = '/^([0-9]+(?:\.[0-9]+)?)('.$accepted.')?$/Di';

        // Verify the size format and store the matching parts
        if (! preg_match($pattern, $size, $matches)) {
            throw new Exception("The byte unit size, [{$size}], is improperly formatted.");
        }

        // Find the float value of the size
        $size = (float) $matches[1];

        // Find the actual unit, assume B if no unit specified
        $unit = array_get($matches, 2, 'B');

        // Convert the size into bytes
        $bytes = $size * pow(2, static::$byte_units[$unit]);

        return $bytes;
    }
}
