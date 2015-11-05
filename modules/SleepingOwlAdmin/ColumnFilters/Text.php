<?php

namespace KodiCMS\SleepingOwlAdmin\ColumnFilters;

class Text extends BaseColumnFilter
{
    /**
     * @var string
     */
    protected $view = 'text';

    /**
     * @var string
     */
    protected $placeholder;

    /**
     * @param string|null $placeholder
     *
     * @return $this|string
     */
    public function placeholder($placeholder = null)
    {
        if (is_null($placeholder)) {
            return $this->placeholder;
        }
        $this->placeholder = $placeholder;

        return $this;
    }

    /**
     * @param        $repository
     * @param        $column
     * @param        $query
     * @param        $search
     * @param        $fullSearch
     * @param string $operator
     */
    public function apply($repository, $column, $query, $search, $fullSearch, $operator = 'like')
    {
        if (empty($search)) {
            return;
        }
        if ($operator == 'like') {
            $search = '%'.$search.'%';
        }
        $name = $column->name();
        if ($repository->hasColumn($name)) {
            $query->where($name, $operator, $search);
        } elseif (strpos($name, '.') !== false) {
            $parts = explode('.', $name);
            $fieldName = array_pop($parts);
            $relationName = implode('.', $parts);
            $query->whereHas($relationName, function ($q) use ($search, $fieldName, $operator) {
                $q->where($fieldName, $operator, $search);
            });
        }
    }

    /**
     * @return array
     */
    protected function getParams()
    {
        return parent::getParams() + [
            'placeholder' => $this->placeholder(),
        ];
    }
}
