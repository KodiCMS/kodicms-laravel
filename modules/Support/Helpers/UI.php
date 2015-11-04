<?php

namespace KodiCMS\Support\Helpers;

/*
 * Class UI
 * TODO: Выпилить статику... Greabock 20.05.2015
 *
 * @package KodiCMS\CMS\Helpers
 */
use HTML;

class UI
{
    /**
     * @param string $name
     * @param array  $attributes
     *
     * @return string HTML
     */
    public static function icon($name, array $attributes = [])
    {
        $attributes = static::buildAttributeClass($attributes, 'fa fa-'.e($name));

        return '<i'.HTML::attributes($attributes).'></i>';
    }

    /**
     * @param string $text
     * @param string $type
     * @param array  $attributes
     *
     * @return string HTML
     */
    public static function label($text, $type = 'info', array $attributes = [])
    {
        $attributes = static::buildAttributeClass($attributes, 'label label-'.e($type));

        return '<span'.HTML::attributes($attributes).'>'.$text.'</span>';
    }

    /**
     * @param string $text
     * @param string $type
     * @param array  $attributes
     *
     * @return string HTML
     */
    public static function badge($text, $type = 'info', array $attributes = [])
    {
        $attributes = static::buildAttributeClass($attributes, 'badge badge-'.e($type));

        return '<span'.HTML::attributes($attributes).'>'.$text.'</span>';
    }

    /**
     * @param string $title
     * @param array  $types
     *
     * @return string
     */
    public static function hidden($title, array $types = ['xs', 'sm'])
    {
        $attributes = ['class' => ''];

        foreach ($types as $type) {
            $attributes['class'] .= ' hidden-'.e($type);
        }

        return '<span'.HTML::attributes($attributes).'>'.$title.'</span>';
    }

    /**
     * @param array        $attributes
     * @param array|string $class
     *
     * @return array
     */
    protected static function buildAttributeClass(array $attributes = [], $class)
    {
        if (! isset($attributes['class'])) {
            $attributes['class'] = [];
        } elseif (! is_array($attributes['class'])) {
            $attributes['class'] = explode(' ', $attributes['class']);
        }

        if (is_array($class)) {
            foreach ($class as $class_name) {
                $attributes['class'][] = $class_name;
            }
        } else {
            $attributes['class'][] = $class;
        }

        $attributes['class'] = implode(' ', array_filter(array_unique($attributes['class'])));

        return $attributes;
    }
}
