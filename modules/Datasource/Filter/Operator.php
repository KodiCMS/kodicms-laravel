<?php

namespace KodiCMS\Datasource\Filter;

use DB;
use Illuminate\Database\Eloquent\Builder;
use KodiCMS\Datasource\Contracts\FilterRuleInterface;
use KodiCMS\Datasource\Contracts\FilterOperatorInterface;
use KodiCMS\Datasource\Exceptions\FilterOperatorException;

abstract class Operator implements FilterOperatorInterface
{
    /**
     * @var FilterRuleInterface
     */
    protected $rule;

    /**
     * @param FilterRuleInterface $rule
     *
     * @throws FilterOperatorException
     */
    public function __construct(FilterRuleInterface $rule)
    {
        $this->rule = $rule;
    }

    /**
     * @return FilterRuleInterface
     */
    public function getRule()
    {
        return $this->rule;
    }

    /**
     * @return string
     */
	public function getValue()
	{
		return $this->getRule()->getValue();
	}

    /**
     * @return string
     */
    public function getCondition()
    {
        return $this->getRule()->getCondition();
    }

    /**
     * @return string
     */
    public function getQueryField()
    {
        $field = $this->getRule()->getField()->getQueryField();

        if ($field instanceof Builder) {
            $sql = $field->toSql();
            foreach($field->getBindings() as $binding)
            {
                $value = is_numeric($binding) ? $binding : "'".$binding."'";
                $sql = preg_replace('/\?/', $value, $sql, 1);
            }

            $field = DB::raw("($sql)");
        }

        return $field;
    }

    /**
     * @param Builder $query
     *
     * @return void
     */
    public function select(Builder $query)
    {
        if (! is_null($select = $this->getRule()->getField()->getSelectField())) {
            if ($select instanceof Builder) {
                $query->selectSub($select->toSql(), $this->getRule()->getField()->getQueryField());
            } else {
                $query->addSelect($select);
            }
        }
    }

    /**
     * @param Builder $query
     */
    public function query(Builder $query)
    {
        if (method_exists($this->getRule()->getField(), 'query')) {
            return $this->rule->getField()->query($query);
        } else {
            return $this->getQuery($query, $this->getQueryField(), $this->getCondition());
        }
    }

    /**
     * @param Builder $query
     * @param string  $field
     * @param string $condition
     */
    public function getQuery(Builder $query, $field, $condition = 'and')
    {
        return $this->_query($query, $field, $condition);
    }

    /**
     * @return bool
     */
    public function isArrayValue()
    {
        return false;
    }

    /**
     * @param Builder $query
     * @param string  $field
     * @param string  $condition
     *
     * @return void
     */
    abstract protected function _query(Builder $query, $field, $condition = 'and');
}
