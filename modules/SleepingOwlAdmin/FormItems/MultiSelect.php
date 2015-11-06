<?php

namespace KodiCMS\SleepingOwlAdmin\FormItems;

use Illuminate\Database\Eloquent\Collection;

class MultiSelect extends Select
{
    /**
     * @var string
     */
    protected $view = 'multiselect';

    /**
     * @return array
     */
    public function getValue()
    {
        $value = parent::getValue();
        if ($value instanceof Collection && $value->count() > 0) {
            $value = $value->lists($value->first()->getKeyName());
        }
        if ($value instanceof Collection) {
            $value = $value->toArray();
        }

        return $value;
    }
}
