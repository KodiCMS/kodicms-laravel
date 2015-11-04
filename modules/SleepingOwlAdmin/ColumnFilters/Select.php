<?php
namespace KodiCMS\SleepingOwlAdmin\ColumnFilters;

use Illuminate\Support\Collection;
use KodiCMS\SleepingOwlAdmin\AssetManager\AssetManager;
use KodiCMS\SleepingOwlAdmin\Repository\BaseRepository;

class Select extends BaseColumnFilter
{
    /**
     * @var string
     */
    protected $view = 'select';

    /**
     * @var
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
     * @var string
     */
    protected $placeholder;

    /**
     * @var string
     */
    protected $filter_field = '';

    /**
     * Initialize column filter
     */
    public function initialize()
    {
        parent::initialize();
        AssetManager::addScript('admin::default/js/columnfilters/select.js');
    }

    /**
     * @param string|null $field
     *
     * @return $this|string
     */
    public function filter_field($field = null)
    {
        if (is_null($field)) {
            return $this->filter_field;
        }
        $this->filter_field = $field;

        return $this;
    }

    /**
     * @param null $model
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
     * @param null $display
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
        $key        = $repository->model()->getKeyName();
        $options    = $repository->query()->get()->lists($this->display(), $key);
        if ($options instanceof Collection) {
            $options = $options->all();
        }
        $options = array_unique($options);
        $this->options($options);
    }

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
     * @return array
     */
    public function getParams()
    {
        return parent::getParams() + [
            'options'     => $this->options(),
            'placeholder' => $this->placeholder(),
        ];
    }

    /**
     * @param        $repository
     * @param        $column
     * @param        $query
     * @param        $search
     * @param        $fullSearch
     * @param string $operator
     */
    public function apply($repository, $column, $query, $search, $fullSearch, $operator = '=')
    {
        #if (empty($search)) return;
        if ($search === '') {
            return;
        }
        if ($this->filter_field()) {
            $query->where($this->filter_field(), '=', $search);

            return;
        }
        if ($operator == 'like') {
            $search = '%'.$search.'%';
        }
        $name = $column->name();
        if ($repository->hasColumn($name)) {
            $query->where($name, $operator, $search);
        } elseif (strpos($name, '.') !== false) {
            $parts        = explode('.', $name);
            $fieldName    = array_pop($parts);
            $relationName = implode('.', $parts);
            $query->whereHas($relationName, function ($q) use ($search, $fieldName, $operator) {
                $q->where($fieldName, $operator, $search);
            });
        }
    }
}
