<?php

namespace KodiCMS\SleepingOwlAdmin\Interfaces;

use Illuminate\Database\Query\Builder;

interface ColumnFilterInterface
{
    /**
     * Initialize column filter.
     */
    public function initialize();

    /**
     * @return array
     */
    public function getParams();

    /**
     * @param RepositoryInterface  $repository
     * @param NamedColumnInterface $column
     * @param Builder              $query
     * @param string               $search
     * @param array|string         $fullSearch
     * @param string               $operator
     *
     * @return void
     */
    public function apply(
        RepositoryInterface $repository,
        NamedColumnInterface $column,
        Builder $query,
        $search,
        $fullSearch,
        $operator = '='
    );
}
