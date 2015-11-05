<?php

namespace KodiCMS\Users\Helpers;

use HTML;

class Gravatar
{
    /**
     * @var array
     */
    protected static $cache = [];

    /**
     * @param string  $email
     * @param int $size
     * @param string  $default
     * @param array   $attributes
     *
     * @return string
     */
    public static function load($email, $size = 100, $default = null, array $attributes = null)
    {
        if (empty($email)) {
            $email = 'test@test.com';
        }

        if ($default === null) {
            $default = 'mm';
        }

        $hash = md5(strtolower(trim($email)));
        $queryParams = http_build_query(['d' => urlencode($default), 's' => (int) $size]);

        if (! isset(self::$cache[$email][$size])) {
            self::$cache[$email][$size] = HTML::image('http://www.gravatar.com/avatar/'.$hash.'?'.$queryParams, null, $attributes);
        }

        return self::$cache[$email][$size];
    }
}
