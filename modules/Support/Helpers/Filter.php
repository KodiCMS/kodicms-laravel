<?php

namespace KodiCMS\Support\Helpers;

use ArrayAccess;
use KodiCMS\CMS\Exceptions\Exception;

class Filter implements ArrayAccess
{
    /**
     * Creates a new Filter instance.
     *
     * @param array      $array array to filter
     * @param array|null $rules rules [field => [...], rules, [...]]
     *
     * @return  Filter
     */
    public static function make(array $array, array $rules = null)
    {
        return new static($array, $rules);
    }

    /**
     * Array to filter.
     *
     * @var array
     */
    protected $filterArray = [];

    /**
     * Field rules.
     *
     * @var array
     */
    protected $rules = [];

    /**
     * Sets the unique "any field" key and creates an ArrayObject from the
     * passed array.
     *
     * @param array      $array array to filter
     * @param array|null $rules rules [field => [...], rules, [...]]
     */
    public function __construct(array $array, array $rules = null)
    {
        $this->filterArray = $array;

        if (! empty($rules)) {
            foreach ($rules as $field => $data) {
                $this->addRules($field, $data);
            }
        }
    }

    /**
     * Returns the array of data to be filter.
     *
     * @return  array
     */
    public function getArray()
    {
        return $this->filterArray;
    }

    /**
     * @param   string   $field   field name
     * @param   callback $rule    valid PHP callback or closure
     * @param   mixed    $default default value
     *
     * @return  $this
     */
    public function addRule($field, $rule, $default = null)
    {
        if (! is_bool($rule) and ! is_null($rule)) {
            // Store the rule and params for this rule
            $this->rules[$field]['rules'][] = $rule;
        }

        $this->rules[$field]['default'] = $default;

        return $this;
    }

    /**
     * Add rules using an array.
     *
     * @param   string|array $field field name
     * @param   array        $rules list of callbacks
     *
     * @return  $this
     */
    public function addRules($field, array $rules = [])
    {
        foreach ($rules as $rule) {
            $this->addRule($field, $rule[0], array_get($rule, 1));
        }

        return $this;
    }

    /**
     * Filters a values.
     */
    public function filter()
    {
        $rules = $this->rules;

        // Get the filters for this column
        $wildcards = empty($rules[true]) ? [] : $rules[true];

        foreach ($rules as $field => $data) {
            $data['rules'] = empty($data['rules']) ? $wildcards : array_merge($wildcards, $data['rules']);

            if ($this->offsetExists($field)) {
                $value = $this->offsetGet($field);
            } elseif (! $this->offsetExists($field) and ! empty($data['default'])) {
                array_set($this->filterArray, $field, $data['default']);
                continue;
            }

            $value = $this->filterFieldValue($field, $value, $data['rules']);
            array_set($this->filterArray, $field, $value);
        }

        return $this;
    }

    /**
     * @param string $field
     * @param mixed  $value
     * @param array  $rules
     *
     * @return mixed
     */
    public function filterFieldValue($field, $value, array $rules = null)
    {
        if (empty($rules)) {
            $rules = array_get($this->rules, $field.'.rules', []);
        }

        // Bind the field name and model so they can be used in the filter method
        $_bound = [':field' => $field, ':filter' => $this];

        foreach ($rules as $filter) {
            // Value needs to be bound inside the loop so we are always using the
            // version that was modified by the filters that already ran
            $_bound[':value'] = $value;
            $params = [':value'];

            foreach ($params as $key => $param) {
                if (is_string($param) and array_key_exists($param, $_bound)) {
                    // Replace with bound value
                    $params[$key] = $_bound[$param];
                }
            }

            $value = Callback::invoke($filter, $params);
        }

        return $value;
    }

    /**
     * Throws an exception because Filter is read-only.
     * Implements ArrayAccess method.
     *
     * @throws  Exception
     *
     * @param   string $offset key to set
     * @param   mixed  $value  value to set
     *
     * @return  void
     */
    public function offsetSet($offset, $value)
    {
        array_set($this->filterArray, $offset, $value);
    }

    /**
     * Checks if key is set in array data.
     * Implements ArrayAccess method.
     *
     * @param   string $offset key to check
     *
     * @return  bool    whether the key is set
     */
    public function offsetExists($offset)
    {
        return array_get($this->filterArray, $offset, '!isset') != '!isset';
    }

    /**
     * Throws an exception because Filter is read-only.
     * Implements ArrayAccess method.
     *
     * @throws  Exception
     *
     * @param   string $offset key to unset
     *
     * @return  void
     */
    public function offsetUnset($offset)
    {
        throw new Exception('Filter objects are read-only.');
    }

    /**
     * Gets a value from the array data.
     * Implements ArrayAccess method.
     *
     * @param   string $offset key to return
     *
     * @return  mixed   value from array
     */
    public function offsetGet($offset)
    {
        return array_get($this->filterArray, $offset);
    }
}
