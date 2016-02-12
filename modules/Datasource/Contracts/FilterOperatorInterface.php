<?php

namespace KodiCMS\Datasource\Contracts;

use Illuminate\Database\Eloquent\Builder;

interface FilterOperatorInterface
{
    /**
     * @param Builder $query
     *
     * @return void
     */
    public function query(Builder $query);

    /**
     * @param Builder $query
     *
     * @return void
     */
    public function select(Builder $query);

    /**
     * @return bool
     */
    public function isArrayValue();
}
