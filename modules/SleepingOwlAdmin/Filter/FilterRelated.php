<?php

namespace KodiCMS\SleepingOwlAdmin\Filter;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class FilterRelated extends FilterBase
{
    /**
     * @var string
     */
    protected $display = 'title';

    /**
     * @var Model
     */
    protected $model;

    /**
     * @param string|null $display
     *
     * @return $this|string
     */
    public function display($display = null)
    {
        if (is_null($display)) {
            return $this->display;
        }
        $this->display = $display;

        return $this;
    }

    /**
     * @param string|null $model
     *
     * @return $this|Model
     */
    public function model($model = null)
    {
        if (is_null($model)) {
            return $this->model;
        }
        $this->model = $model;

        return $this;
    }

    /**
     * @param string|null $title
     *
     * @return $this|null|string
     * @throws Exception
     */
    public function title($title = null)
    {
        $parent = parent::title($title);
        if (is_null($parent)) {
            return $this->getDisplayField();
        }

        return $parent;
    }

    /**
     * @return null
     * @throws Exception
     */
    protected function getDisplayField()
    {
        $model = $this->model();
        if (is_null($model)) {
            throw new Exception('Specify model for filter: '.$this->name());
        }
        try {
            $instance = app($model)->findOrFail($this->value());

            return $instance->{$this->display()};
        } catch (ModelNotFoundException $e) {
        }

        return;
    }
}
