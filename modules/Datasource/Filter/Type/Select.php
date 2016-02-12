<?php

namespace KodiCMS\Datasource\Filter\Type;

use Closure;
use KodiCMS\Datasource\Filter\Type;

class Select extends Type
{
    /**
     * @var array
     */
    protected $operators = ['equal', 'not_equal', 'in', 'not_in'];

    /**
     * @var string
     */
    protected $type = 'integer';

    /**
     * @var string
     */
    protected $input = 'select';

    /**
     * @var bool
     */
    protected $isMultiple = false;

    /**
     * @var array
     */
    protected $values = [];

    /**
     * @param string     $id
     * @param string     $label
     * @param string     $tableField
     * @param Closure    $values
     * @param bool|false $isMultiple
     * @param array|null $operators
     */
    public function __construct($id, $label, $tableField, Closure $values, $isMultiple = false, array $operators = null)
    {
        parent::__construct($id, $label, $tableField, $operators);

        $this->values = $values;
        $this->isMultiple = $isMultiple;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return array_merge(parent::toArray(), [
            'input'    => $this->input,
            'multiple' => $this->isMultiple,
            'values'   => call_user_func($this->values),
        ]);
    }
}
