<?php

namespace KodiCMS\Datasource\Filter;

use DB;
use Exception;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use KodiCMS\Datasource\Contracts\FilterTypeInterface;
use KodiCMS\Datasource\Contracts\FilterRuleInterface;
use KodiCMS\Datasource\Model\Field;

class Type implements FilterTypeInterface
{
    /**
     * @var array
     */
    protected $operators = null;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var string
     */
    protected $id;

    /**
     * @var string
     */
    protected $input = 'string';

    /**
     * @var string
     */
    protected $tableField;

    /**
     * @var string
     */
    protected $label;

    /**
     * @var Operator
     */
    protected $operator;

    /**
     * @var FilterRuleInterface
     */
    protected $rule;

    /**
     * @var bool
     */
    protected $is_required = true;

    /**
     * @param Field $field
     */
    public function __construct(Field $field)
    {
        $this->id         = $field->getId();
        $this->label      = $field->getName();
        $this->tableField = $field->getSection()->getSectionTableName() . '.' . $field->getDBKey();

        if (method_exists($field, 'getFilterOperators')) {
            $this->operators = $field->getFilterOperators();
        }
    }

    /**
     * @param FilterRuleInterface $rule
     * @param Builder             $query
     *
     * @return bool
     */
    public function setRule(FilterRuleInterface $rule, Builder $query)
    {
        $this->rule = $rule;
        $this->rule->setField($this);

        $this->rule->getOperator()->select($query);
        $this->rule->getOperator()->query($query);
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
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @return string
     */
    public function notRequired()
    {
        $this->is_required = false;

        return $this;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getInput()
    {
        return $this->input;
    }

    /**
     * @return array|null
     */
    public function getSelectField()
    {
        return;
    }

    /**
     * @return array|null
     */
    public function getOperators()
    {
        return $this->operators;
    }

    /**
     * @param string $value
     *
     * @return string
     */
    public function parseValue($value)
    {
        return $value;
    }

    /**
     * @return array|null
     */
    public function getQueryField()
    {
        return $this->tableField;
    }

    /**
     * @return array|null
     */
    public function getAdditionalInputs()
    {
        return null;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'id'        => $this->getId(),
            'label'     => $this->getLabel(),
            'input'     => $this->getInput(),
            'type'      => $this->getType(),
            'operators' => $this->getOperators(),
            'inputs'    => $this->getAdditionalInputs(),
            'required'  => (bool) $this->is_required
        ];
    }

    /**
     * @param string $date
     * @param string $format
     *
     * @return mixed
     * @see http://php.net/manual/en/datetime.formats.relative.php
     */
    public function parseDate($date, $format)
    {
        if (empty($date)) {
            return null;
        }

        if ($this->isMysqlFunction($date)) {
            return DB::raw($date);
        }

        try {
            return Carbon::parse($date)->format($format);
        } catch (Exception $e) {
            return '';
        }
    }

    /**
     * @param string $string
     *
     * @return bool|int
     */
    protected function isMysqlFunction($string)
    {
        if (! is_string($string)) {
            return false;
        }

        $functions = [
            'curdate',
            'current_date',
            'curtime',
        ];

        return preg_match('/'.implode('|', $functions).'/', $string);
    }
}
