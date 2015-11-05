<?php

namespace KodiCMS\SleepingOwlAdmin\Filter;

use Illuminate\Database\Query\Builder;

class FilterScope extends FilterField
{
    /**
     * @param Builder $query
     */
    public function apply(Builder $query)
    {
        call_user_func([$query, $this->name()], $this->value());
    }
}
