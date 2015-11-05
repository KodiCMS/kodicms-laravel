<?php

namespace KodiCMS\SleepingOwlAdmin\Filter;

use Closure;
use Illuminate\Database\Query\Builder;

class FilterCustom extends FilterField
{
    /**
     * @var Closure|array
     */
    protected $callback;

    /**
     * @param Builder $query
     */
    public function apply(Builder $query)
    {
        call_user_func($this->callback(), $query, $this->value());
    }

    /**
     * @param Closure|array|null $callback
     *
     * @return $this|Closure|array
     */
    public function callback($callback = null)
    {
        if (is_null($callback)) {
            return $this->callback;
        }
        $this->callback = $callback;

        return $this;
    }
}
