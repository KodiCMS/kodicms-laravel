<?php

namespace KodiCMS\Support\Model\Fields;

use Form;

class TextAreaField extends KodiCMSField
{
    /**
     * @param string $name
     * @param mixed  $value
     * @param array  $attributes
     *
     * @return mixed
     */
    protected function getFormFieldHTML($name, $value, array $attributes)
    {
        return Form::textarea($name, $value, $attributes);
    }
}
