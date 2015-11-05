<?php

namespace KodiCMS\Support\Model\Fields;

use Form;

class PasswordField extends TextField
{
    protected function boot()
    {
        parent::boot();

        $this->getGroup()->setSettings([
                'fieldCol' => 'col-md-4',
            ]);
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
        $this->setAttributes([
            'autocomplete' => 'off',
        ]);

        return Form::password($name, $this->getAttributes());
    }
}
