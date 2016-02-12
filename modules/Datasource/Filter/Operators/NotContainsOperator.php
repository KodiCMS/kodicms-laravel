<?php

namespace KodiCMS\Datasource\Filter\Operators;

use Illuminate\Database\Eloquent\Builder;

class NotContainsOperator extends ContainsOperator
{
    /**
     * @param Builder $query
     * @param string  $field
     * @param string  $condition
     */
    protected function _query(Builder $query, $field, $condition = 'and')
    {
        $query->where($field, 'not like', "%{$this->getValue()}%", $condition);
    }
}
