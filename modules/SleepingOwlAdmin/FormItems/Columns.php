<?php

namespace KodiCMS\SleepingOwlAdmin\FormItems;

use Closure;
use Illuminate\Database\Eloquent\Model;
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

    public function initialize()
    {
        parent::initialize();
        $this->all(function ($item) {
            $item->initialize();
        });
    }

    /**
     * @return array
     */
    public function getColumns()
    {
        return $this->columns;
    }

    /**
     * @param array $columns
     *
     * @return $this
     */
    public function setColumns($columns)
    {
        $this->columns = $columns;

        return $this;
    }

    /**
     * @param Model $model
     *
     * @return $this
     */
    public function setModel(Model $model)
    {
        parent::setModel($model);
        $this->all(function ($item) use ($model) {
            $item->setModel($model);
        });

        return $this;
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return parent::getParams() + [
            'columns' => $this->getColumns(),
        ];
    }

    /**
     * @return array
     */
    public function getValidationRules()
    {
        $rules = parent::getValidationRules();
        foreach ($this->getColumns() as $columnItems) {
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

    /**
     * @param Closure $callback
     */
    protected function all(Closure $callback)
    {
        foreach ($this->getColumns() as $columnItems) {
            foreach ($columnItems as $item) {
                if ($item instanceof FormItemInterface) {
                    call_user_func($callback, $item);
                }
            }
        }
    }
}
