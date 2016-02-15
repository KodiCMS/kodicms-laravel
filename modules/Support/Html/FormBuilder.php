<?php

namespace KodiCMS\Support\Html;

class FormBuilder extends \Collective\Html\FormBuilder
{
    /**
     * @param string    $name
     * @param int       $value
     * @param bool|null $checked
     * @param array     $options
     *
     * @return string
     */
    public function switcher($name, $value = 1, $checked = null, $options = [])
    {
        $options = array_merge([
            'class'        => 'form-switcher',
            'data-size'    => 'small',
            'data-width'   => 60,
            'data-on'      => trans('cms::system.button.on'),
            'data-off'     => trans('cms::system.button.off'),
            'data-onstyle' => 'success',
        ], $options);

        return $this->checkbox($name, $value, $checked, $options);
    }
}
