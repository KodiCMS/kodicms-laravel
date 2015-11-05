<?php

namespace KodiCMS\SleepingOwlAdmin\Interfaces;

use Illuminate\Database\Query\Builder;

interface FilterInterface
{
    /**
     * Initialize filter.
     */
    public function initialize();

    /**
     * Is filter active?
     */
    public function isActive();

    /**
     * Apply filter to the query.
     *
     * @param Builder $query
     */
    public function apply(Builder $query);
}
