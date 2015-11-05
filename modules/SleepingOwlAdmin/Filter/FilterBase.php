<?php

namespace KodiCMS\SleepingOwlAdmin\Filter;

use Input;
use Illuminate\Database\Query\Builder;
use KodiCMS\SleepingOwlAdmin\Interfaces\FilterInterface;

abstract class FilterBase implements FilterInterface
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $alias;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var mixed
     */
    protected $value;

    /**
     * @param string $name
     */
    public function __construct($name)
    {
        $this->name($name);
        $this->alias($name);
    }

    /**
     * @param string|null $name
     *
     * @return $this|string
     */
    public function name($name = null)
    {
        if (is_null($name)) {
            return $this->name;
        }
        $this->name = $name;

        return $this;
    }

    /**
     * @param string|null $alias
     *
     * @return $this|string
     */
    public function alias($alias = null)
    {
        if (is_null($alias)) {
            return $this->alias;
        }
        $this->alias = $alias;

        return $this;
    }

    /**
     * @param string|null $title
     *
     * @return $this|string
     */
    public function title($title = null)
    {
        if (is_null($title)) {
            if (is_callable($this->title)) {
                return call_user_func($this->title, $this->value());
            }

            return $this->title;
        }
        $this->title = $title;

        return $this;
    }

    /**
     * @param mixed|null $value
     *
     * @return $this|mixed
     */
    public function value($value = null)
    {
        if (is_null($value)) {
            return $this->value;
        }
        $this->value = $value;

        return $this;
    }

    public function initialize()
    {
        $parameters = Input::all();
        $value = $this->value();
        if (is_null($value)) {
            $value = array_get($parameters, $this->alias());
        }
        $this->value($value);
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        return ! is_null($this->value());
    }

    /**
     * @param Builder $query
     */
    public function apply(Builder $query)
    {
        $query->where($this->name(), $this->value());
    }
}
