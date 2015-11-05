<?php

namespace KodiCMS\Support\Model\Fields;

use Form;
use KodiCMS\Support\Helpers\Callback;

class SelectField extends KodiCMSField
{
    protected function boot()
    {
        parent::boot();

        $this->getGroup()->setSettings([
                'fieldCol' => 'col-md-6',
            ]);
    }

    /**
     * @param string $key
     * @param mixed  $value
     *
     * @return mixed
     */
    public function getOptions($key, $value)
    {
        if (isset($this->callbackOptions)) {
            return Callback::invoke($this->callbackOptions, [$value], [
                '{model}' => $this->model,
            ]);
        }

        if ($this->hasGetOptionsMethod($key)) {
            return $this->callGetOptionsMethod($key, $value);
        }

        return [];
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
        return Form::select($name, $this->getOptions($name, $value), $value, $attributes);
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    protected function hasGetOptionsMethod($key)
    {
        return method_exists($this->model, 'getField'.studly_case($key).'Options');
    }

    /**
     * @param string $key
     * @param mixed  $value
     *
     * @return mixed
     */
    protected function callGetOptionsMethod($key, $value)
    {
        return $this->model->{'getField'.studly_case($key).'Options'}($value);
    }
}
