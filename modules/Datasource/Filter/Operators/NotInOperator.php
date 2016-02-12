<?php

namespace KodiCMS\Datasource\Filter\Operators;

use Illuminate\Database\Eloquent\Builder;

class NotInOperator extends InOperator
{
    /**
     * @param Builder $query
     * @param string  $field
     * @param string  $condition
     */
    protected function _query(Builder $query, $field, $condition = 'and')
    {
        $query->whereNotIn($field, $this->getValue(), $condition);
    }
}
