<?php

namespace KodiCMS\SleepingOwlAdmin\FormItems;

use Closure;
use KodiCMS\SleepingOwlAdmin\Interfaces\FormItemInterface;

class Columns extends BaseFormItem
{
    /**
     * @var string
     */
    protected $view = 'columns';

    /**
     * @var array
     */
    protected $columns = [];

    /**
     * @param array|null $columns
     *
     * @return $this|array
     */
    public function columns($columns = null)
    {
        if (is_null($columns)) {
            return $this->columns;
        }
        $this->columns = $columns;

        return $this;
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return parent::getParams() + [
            'columns' => $this->columns(),
        ];
    }

    /**
     * @return array
     */
    public function getValidationRules()
    {
        $rules = parent::getValidationRules();
        foreach ($this->columns() as $columnItems) {
            foreach ($columnItems as $item) {
                if ($item instanceof FormItemInterface) {
                    $rules += $item->getValidationRules();
                }
            }
        }

        return $rules;
    }

    public function save()
    {
        parent::save();
        $this->all(function ($item) {
            $item->save();
        });
    }

    public function initialize()
    {
        parent::initialize();
        $this->all(function ($item) {
            $item->initialize();
        });
    }

    public function setInstance($instance)
    {
        parent::setInstance($instance);
        $this->all(function ($item) use ($instance) {
            $item->setInstance($instance);
        });

        return $this->instance($instance);
    }

    /**
     * @param Closure $callback
     */
    protected function all($callback)
    {
        foreach ($this->columns() as $columnItems) {
            foreach ($columnItems as $item) {
                if ($item instanceof FormItemInterface) {
                    $callback($item);
                }
            }
        }
    }
}
