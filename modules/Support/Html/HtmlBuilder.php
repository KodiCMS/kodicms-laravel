<?php

namespace KodiCMS\Support\Html;

class HtmlBuilder extends \Collective\Html\HtmlBuilder
{
    /**
     * Build a single attribute element.
     *
     * @param  string $key
     * @param  string $value
     *
     * @return string
     */
    protected function attributeElement($key, $value)
    {
        if (is_numeric($key)) {
            $key = $value;
        }

        if (is_array($value)) {
            $value = implode(' ', $value);
        }

        if (! is_null($value)) {
            return $key.'="'.e($value).'"';
        }
    }

    /**
     * Generate a HTML link.
     *
     * @param  string $url
     * @param  string $title
     * @param  array  $attributes
     * @param  bool   $secure
     *
     * @return string
     */
    public function link($url, $title = null, $attributes = [], $secure = null)
    {
        $url = $this->url->to($url, [], $secure);

        if (is_null($title) || $title === false) {
            $title = $url;
        }

        return '<a href="'.$url.'"'.$this->attributes($attributes).'>'.$title.'</a>';
    }
}
