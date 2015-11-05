<?php

namespace KodiCMS\SleepingOwlAdmin\FormItems;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use KodiCMS\SleepingOwlAdmin\Repository\BaseRepository;

class Select extends NamedFormItem
{
    /**
     * @var string
     */
    protected $view = 'select';

    /**
     * @var Model
     */
    protected $model;

    /**
     * @var string
     */
    protected $display = 'title';

    /**
     * @var array
     */
    protected $options = [];

    /**
     * @var bool
     */
    protected $nullable = false;

    /**
     * @param string|null $model
     *
     * @return $this
     */
    public function model($model = null)
    {
        if (is_null($model)) {
            return $this->model;
        }
        $this->model = $model;

        return $this;
    }

    /**
     * @param string|null $display
     *
     * @return $this|string
     */
    public function display($display = null)
    {
        if (is_null($display)) {
            return $this->display;
        }
        $this->display = $display;

        return $this;
    }

    /**
     * @param array|null $options
     *
     * @return $this|array|null
     */
    public function options($options = null)
    {
        if (is_null($options)) {
            if (! is_null($this->model()) && ! is_null($this->display())) {
                $this->loadOptions();
            }
            $options = $this->options;
            asort($options);

            return $options;
        }
        $this->options = $options;

        return $this;
    }

    protected function loadOptions()
    {
        $repository = new BaseRepository($this->model());
        $key = $repository->model()->getKeyName();
        $options = $repository->query()->get()->lists($this->display(), $key);
        if ($options instanceof Collection) {
            $options = $options->all();
        }
        $this->options($options);
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return parent::getParams() + [
            'options'  => $this->options(),
            'nullable' => $this->isNullable(),
        ];
    }

    /**
     * @param array $values
     *
     * @return array
     */
    public function enum($values)
    {
        return $this->options(array_combine($values, $values));
    }

    /**
     * @param bool $nullable
     *
     * @return $this
     */
    public function nullable($nullable = true)
    {
        $this->nullable = $nullable;

        return $this;
    }

    /**
     * @return bool
     */
    public function isNullable()
    {
        return $this->nullable;
    }
}
