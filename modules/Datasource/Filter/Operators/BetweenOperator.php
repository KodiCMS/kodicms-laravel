<?php

namespace KodiCMS\Datasource\Filter\Operators;

use KodiCMS\Datasource\Filter\Operator;
use Illuminate\Database\Eloquent\Builder;

class BetweenOperator extends Operator
{
    /**
     * @return bool
     */
    public function isArrayValue()
    {
        return true;
    }

    /**
     * @param Builder $query
     * @param string  $field
     * @param string  $condition
     */
    protected function _query(Builder $query, $field, $condition = 'and')
    {
        $query->whereBetween($field, $this->getValue(), $condition);
    }
}
