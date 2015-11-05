<?php

namespace KodiCMS\SleepingOwlAdmin\ColumnFilters;

use Illuminate\Contracts\Support\Renderable;
use KodiCMS\SleepingOwlAdmin\Interfaces\ColumnFilterInterface;

abstract class BaseColumnFilter implements Renderable, ColumnFilterInterface
{
    /**
     * @var string
     */
    protected $view;

    /**
     * Initialize column filter.
     */
    public function initialize()
    {
    }

    /**
     * @return array
     */
    protected function getParams()
    {
        return [];
    }

    /**
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function render()
    {
        return app('sleeping_owl.template')->view('columnfilter.'.$this->view, $this->getParams());
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->render();
    }
}
