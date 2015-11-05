<?php

namespace KodiCMS\SleepingOwlAdmin\ColumnFilters;

class Range extends BaseColumnFilter
{
    /**
     * @var string
     */
    protected $view = 'range';

    /**
     * @var string
     */
    protected $from;

    /**
     * @var string
     */
    protected $to;

    /**
     * Initialize column filter.
     */
    public function initialize()
    {
        parent::initialize();

        $this->from()->initialize();
        $this->to()->initialize();
    }

    /**
     * @param areing|null $from
     *
     * @return $this|string
     */
    public function from($from = null)
    {
        if (is_null($from)) {
            return $this->from;
        }
        $this->from = $from;

        return $this;
    }

    /**
     * @param string|null $to
     *
     * @return $this|string
     */
    public function to($to = null)
    {
        if (is_null($to)) {
            return $this->to;
        }
        $this->to = $to;

        return $this;
    }

    /**
     * @return array
     */
    protected function getParams()
    {
        return parent::getParams() + [
            'from' => $this->from(),
            'to'   => $this->to(),
        ];
    }

    /**
     * @param        $repository
     * @param        $column
     * @param        $query
     * @param        $search
     * @param        $fullSearch
     * @param string $operator
     */
    public function apply($repository, $column, $query, $search, $fullSearch, $operator = '=')
    {
        $from = array_get($fullSearch, 'from');
        $to = array_get($fullSearch, 'to');
        if (! empty($from)) {
            $this->from()->apply($repository, $column, $query, $from, $fullSearch, '>=');
        }
        if (! empty($to)) {
            $this->to()->apply($repository, $column, $query, $to, $fullSearch, '<=');
        }
    }
}
