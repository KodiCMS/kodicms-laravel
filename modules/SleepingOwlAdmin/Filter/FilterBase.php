<?php

namespace KodiCMS\SleepingOwlAdmin\Filter;

use Input;
use Closure;
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
        $this->setName($name);
        $this->setAlias($name);
    }

    public function initialize()
    {
        $parameters = Input::all();
        $value = $this->getValue();
        if (is_null($value)) {
            $value = array_get($parameters, $this->getAlias());
        }
        $this->setValue($value);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * @param string $alias
     *
     * @return $this
     */
    public function setAlias($alias)
    {
        $this->alias = $alias;

        return $this;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        if (is_callable($this->title)) {
            return call_user_func($this->title, $this->getValue());
        }

        return $this->title;
    }

    /**
     * @param Closure|string $title
     *
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     *
     * @return $this
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        return ! is_null($this->getValue());
    }

    /**
     * @param Builder $query
     */
    public function apply(Builder $query)
    {
        $query->where($this->getName(), $this->getValue());
    }
}
