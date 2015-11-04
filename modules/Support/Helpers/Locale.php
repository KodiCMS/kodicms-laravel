<?php

namespace KodiCMS\Support\Helpers;

use Request;

/**
 * Class Locale.
 */
class Locale
{
    const DEFAULT_LOCALE = 'sys';

    /**
     * @return string
     */
    public static function detectBrowser()
    {
        return substr(Request::server('http_accept_language'), 0, 2);
    }

    /**
     * @return array
     */
    public static function getAvailable()
    {
        return config('cms.locales', []);
    }

    /**
     * @return string
     */
    public static function getSystemDefault()
    {
        return config('app.locale', 'en');
    }
}
