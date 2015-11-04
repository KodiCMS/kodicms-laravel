<?php

namespace KodiCMS\Support\Model\Fields;

use Form;

class CheckboxField extends KodiCMSField
{
    /**
     * @var string
     */
    protected $template = 'cms::model_fields.checkbox';

    protected function boot()
    {
        $this->getGroup()->setAttributes([
            'class' => ['form-group'],
        ])->setSettings([
            'fieldCol' => 'col-md-9 col-md-offset-3',
        ])->setTemplate($this->template);
    }

    /**
     * @param string $name
     * @param mixed  $value
     * @param array  $attributes
     *
     * @return mixed
     */
    protected function getFormFieldHTML($name, $value, array $attributes)
    {
        return Form::checkbox($name, 1, $value, $attributes);
    }
}
