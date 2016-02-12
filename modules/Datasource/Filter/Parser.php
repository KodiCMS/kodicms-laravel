<?php

namespace KodiCMS\Datasource\Filter;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;
use KodiCMS\Datasource\Exceptions\FilterParserException;
use KodiCMS\Datasource\Fields\FieldsCollection;
use KodiCMS\Datasource\Model\Field;

class Parser
{
    /**
     * @var string
     */
    protected $rules;

    /**
     * @var Builder
     */
    protected $query;

    /**
     * @var FieldsCollection
     */
    public $fields;

    /**
     * @param string           $rules
     * @param FieldsCollection $fields
     *
     * @throws FilterParserException
     */
    public function __construct($rules, FieldsCollection $fields)
    {
        if (is_string($rules)) {
            if (! $this->parseJson($rules)) {
                throw new FilterParserException('Json not valid');
            }
        } else {
            $this->rules = $rules;
        }

        $this->fields = $fields;
    }

    /**
     * @param Builder $query
     *
     * @return Builder
     */
    public function compile(Builder $query)
    {
        $query->where(function ($query) {
            if (is_object($this->rules) and property_exists($this->rules, 'rules')) {
                $this->parseGroup($query, $this->rules->condition, $this->rules->rules);
            }
        });
    }

    /**
     * @param Builder $query
     * @param string  $condition
     * @param array   $rules
     */
    protected function parseGroup(Builder $query, $condition, array $rules)
    {
        foreach ($rules as $rule) {
            $query->where(function (Builder $q) use ($condition,  $rule) {
                if (isset($rule->condition) and isset($rule->rules)) {
                    $this->parseGroup($q, $rule->condition, $rule->rules);
                } else {

                    // Если для поля имеется кастомный класс правила, то загружаем его, если нет, то загружаем стандартный
                    if (!isset( $rule->type ) or ! class_exists($ruleClass = 'KodiCMS\Datasource\Filter\Rules\\' . Str::studly($rule->type) . 'Rule')) {
                        $ruleClass = 'KodiCMS\Datasource\Filter\Rule';
                    }

                    $this->parseRule($q, new $ruleClass($rule, $condition));
                }
            }, null, null, $condition);
        }
    }

    /**
     * @param Builder  $query
     * @param Rule $rule
     *
     * @return bool
     */
    protected function parseRule(Builder $query, Rule $rule)
    {
        /** @var Field $field */
        $field = $this->fields->getById($rule->getField());

        if ($field instanceof Field) {
            $field->getFilterType()->setRule($rule, $query);
        }
    }

    /**
     * @param string $string
     *
     * @return bool
     */
    protected function parseJson($string)
    {
        $this->rules = json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }
}
