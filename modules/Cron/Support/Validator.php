<?php

namespace KodiCMS\Cron\Support;

class Validator
{
    const REGEX = '/^((\*(\/[0-9]+)?)|[0-9\-\,\/]+)\s+((\*(\/[0-9]+)?)|[0-9\-\,\/]+)\s+((\*(\/[0-9]+)?)|[0-9\-\,\/]+)\s+((\*(\/[0-9]+)?)|[0-9\-\,\/]+)\s+((\*(\/[0-9]+)?)|[0-9\-\,\/]+)$/i';

    /**
     * @param        $attribute
     * @param string $value
     *
     * @return bool
     */
    public function validateCrontab($attribute, $value)
    {
        return (bool) preg_match(static::REGEX, trim($value));
    }
}
