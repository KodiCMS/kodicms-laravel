<?php

namespace KodiCMS\SleepingOwlAdmin\Interfaces;

interface ColumnFilterInterface
{
    /**
     * Initialize column filter.
     */
    public function initialize();

    /**
     * @param        $repository
     * @param        $column
     * @param        $query
     * @param        $search
     * @param        $fullSearch
     * @param string $operator
     *
     * @return mixed
     */
    public function apply($repository, $column, $query, $search, $fullSearch, $operator = '=');
}
