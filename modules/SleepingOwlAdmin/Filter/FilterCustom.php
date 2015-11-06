<?php

namespace KodiCMS\SleepingOwlAdmin\Filter;

use Closure;
use Illuminate\Database\Query\Builder;

class FilterCustom extends FilterField
{
    /**
     * @var Closure
     */
    protected $callback;

    /**
     * @param Builder $query
     */
    public function apply(Builder $query)
    {
        call_user_func($this->getCallback(), $query, $this->getValue());
    }

    /**
     * @return Closure
     */
    public function getCallback()
    {
        return $this->callback;
    }

    /**
     * @param Closure $callback
     *
     * @return $this
     */
    public function setCallback(Closure $callback)
    {
        $this->callback = $callback;

        return $this;
    }
}
