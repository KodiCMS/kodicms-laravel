<?php

namespace KodiCMS\SleepingOwlAdmin\FormItems;

use Input;

class Checkbox extends NamedFormItem
{
    /**
     * @var string
     */
    protected $view = 'checkbox';

    public function save()
    {
        $name = $this->getName();
        if (! Input::has($name)) {
            Input::merge([$name => 0]);
        }
        parent::save();
    }
}
