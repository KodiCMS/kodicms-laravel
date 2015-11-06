<?php

namespace KodiCMS\SleepingOwlAdmin\Display;

use KodiCMS\SleepingOwlAdmin\Interfaces\ColumnFilterInterface;

class DisplayDatatables extends DisplayTable
{
    /**
     * View to render.
     * @var string
     */
    protected $view = 'datatables';

    /**
     * @var array
     */
    protected $order = [[0, 'asc']];

    /**
     * @var ColumnFilterInterface[]
     */
    protected $columnFilters = [];

    /**
     * @var array
     */
    protected $attributes = [];

    /**
     * Initialize display.
     */
    public function initialize()
    {
        parent::initialize();
        foreach ($this->getColumnFilters() as $columnFilter) {
            if ($columnFilter instanceof ColumnFilterInterface) {
                $columnFilter->initialize();
            }
        }
    }

    /**
     * @return array
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @param array $order
     *
     * @return $this
     */
    public function setOrder($order)
    {
        if (! is_array($order)) {
            $order = func_get_args();
        }
        $this->order = $order;

        return $this;
    }

    /**
     * @return ColumnFilterInterface[]
     */
    public function getColumnFilters()
    {
        return $this->columnFilters;
    }

    /**
     * @param array|ColumnFilterInterface $columnFilters
     *
     * @return $this
     */
    public function setColumnFilters($columnFilters)
    {
        if (! is_array($columnFilters)) {
            $columnFilters = func_get_args();
        }
        $this->columnFilters = $columnFilters;

        return $this;
    }

    /**
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * @param array|string $attributes
     *
     * @return $this
     */
    public function setAttributes($attributes)
    {
        if (! is_array($attributes)) {
            $attributes = func_get_args();
        }
        $this->attributes = $attributes;

        return $this;
    }

    /**
     * Get view render parameters.
     * @return array
     */
    public function getParams()
    {
        $params = parent::getParams();
        $params['order'] = $this->getOrder();
        $params['columnFilters'] = $this->getColumnFilters();
        $params['attributes'] = $this->getAttributes();

        return $params;
    }
}
