<?php

namespace KodiCMS\SleepingOwlAdmin\Columns\Column;

class Filter extends NamedColumn
{
    /**
     * Filter related model.
     * @var string
     */
    protected $model;

    /**
     * Field to get filter value from.
     * @var string
     */
    protected $field;

    /**
     * Get or set filter related model.
     *
     * @param string|null $model
     *
     * @return $this|string
     */
    public function model($model = null)
    {
        if (is_null($model)) {
            if (is_null($this->model)) {
                $this->model(get_class($this->instance));
            }

            return $this->model;
        }
        $this->model = $model;

        return $this;
    }

    /**
     * Get or set field to get filter value from.
     *
     * @param string|null $field
     *
     * @return $this|string
     */
    public function field($field = null)
    {
        if (is_null($field)) {
            if (is_null($this->field)) {
                $this->field($this->isSelf() ? $this->name() : 'id');
            }

            return $this->field;
        }
        $this->field = $field;

        return $this;
    }

    /**
     * Get filter url.
     * @return string
     */
    public function getUrl()
    {
        $value = $this->getValue($this->instance, $this->field());

        return app('sleeping_owl.admin')->getModel($this->model)->displayUrl([$this->name() => $value]);
    }

    /**
     * Check if filter applies to the current model.
     * @return bool
     */
    protected function isSelf()
    {
        return get_class($this->instance) == $this->model();
    }

    /**
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function render()
    {
        return app('sleeping_owl.template')->view('column.filter', [
            'isSelf' => $this->isSelf(),
            'url'    => $this->getUrl(),
            'value'  => $this->getValue($this->instance, $this->field()),
        ]);
    }
}
