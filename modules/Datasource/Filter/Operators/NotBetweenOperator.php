<?php

namespace KodiCMS\Datasource\Filter\Operators;

use Illuminate\Database\Eloquent\Builder;

class NotBetweenOperator extends BetweenOperator
{
    /**
     * @param Builder $query
     * @param string  $field
     * @param string  $condition
     */
    protected function _query(Builder $query, $field, $condition = 'and')
    {
        $query->whereNotBetween($field, $this->getValue(), $condition);
    }
}
