<?php

namespace KodiCMS\Widgets\Contracts;

interface WidgetManager
{
    /**
     * @return array
     */
    public static function getAvailableTypes();

    /**
     * @param string $needleType
     *
     * @return string|null
     */
    public static function getClassNameByType($needleType);

    /**
     * @param string      $type
     * @param string      $name
     * @param string|null $description
     * @param array|null  $settings
     *
     * @return Widget|null
     */
    public static function makeWidget($type, $name, $description = null, array $settings = null);
}
