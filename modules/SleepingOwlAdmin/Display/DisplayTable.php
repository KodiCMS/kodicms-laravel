<?php

namespace KodiCMS\SleepingOwlAdmin\Display;

use Illuminate\Contracts\Support\Renderable;
use Input;
use KodiCMS\SleepingOwlAdmin\Columns\Column;
use KodiCMS\SleepingOwlAdmin\Repository\BaseRepository;
use KodiCMS\SleepingOwlAdmin\Interfaces\ColumnInterface;
use KodiCMS\SleepingOwlAdmin\Interfaces\DisplayInterface;

class DisplayTable implements Renderable, DisplayInterface
{
    /**
     * @var string
     */
    protected $view = 'table';

    /**
     * @var string
     */
    protected $class;

    /**
     * @var array
     */
    protected $columns = [];

    /**
     * @var array
     */
    protected $with = [];

    /**
     * @var BaseRepository
     */
    protected $repository;

    protected $apply;

    /**
     * @var array
     */
    protected $scopes = [];

    /**
     * @var array
     */
    protected $filters = [];

    /**
     * @var array
     */
    protected $activeFilters = [];

    /**
     * @var bool
     */
    protected $controlActive = true;

    /**
     * @var array
     */
    protected $parameters = [];

    /**
     * @var array
     */
    protected $actions = [];

    /**
     * @param string $class
     */
    public function setClass($class)
    {
        if (is_null($this->class)) {
            $this->class = $class;
        }
    }

    /**
     * @param array|null $columns
     *
     * @return $this|array
     */
    public function columns($columns = null)
    {
        if (is_null($columns)) {
            return $this->columns;
        }

        $this->columns = $columns;

        return $this;
    }

    /**
     * @return array
     */
    public function allColumns()
    {
        $columns = $this->columns();
        if ($this->controlActive()) {
            $columns[] = Column::control();
        }

        return $columns;
    }

    /**
     * @param array|null $with
     *
     * @return $this|array
     */
    public function with($with = null)
    {
        if (is_null($with)) {
            return $this->with;
        }
        if (! is_array($with)) {
            $with = func_get_args();
        }
        $this->with = $with;

        return $this;
    }

    /**
     * @param array|null $filters
     *
     * @return $this|array
     */
    public function filters($filters = null)
    {
        if (is_null($filters)) {
            return $this->filters;
        }
        $this->filters = $filters;

        return $this;
    }

    /**
     * @param array|null $apply
     *
     * @return $this
     */
    public function apply($apply = null)
    {
        if (is_null($apply)) {
            return $this->apply;
        }
        $this->apply = $apply;

        return $this;
    }

    /**
     * @param array|null $scope
     *
     * @return $this|array
     */
    public function scope($scope = null)
    {
        if (is_null($scope)) {
            return $this->scopes;
        }
        $this->scopes[] = func_get_args();

        return $this;
    }

    /**
     * @return string
     */
    public function title()
    {
        $titles = array_map(function ($filter) {
            return $filter->title();
        }, $this->activeFilters);

        return implode(', ', $titles);
    }

    public function initialize()
    {
        $this->repository = new BaseRepository($this->class);
        $this->repository->with($this->with());

        $this->initializeFilters();

        foreach ($this->allColumns() as $column) {
            if ($column instanceof ColumnInterface) {
                $column->initialize();
            }
        }
    }

    protected function initializeAction()
    {
        $action = Input::get('_action');
        $id = Input::get('_id');
        $ids = Input::get('_ids');
        if (! is_null($action) && (! is_null($id) || ! is_null($ids))) {
            $columns = array_merge($this->columns(), $this->actions());
            foreach ($columns as $column) {
                if (! $column instanceof Column\NamedColumn) {
                    continue;
                }

                if ($column->name() == $action) {
                    $param = null;
                    if (! is_null($id)) {
                        $param = $this->repository->find($id);
                    } else {
                        $ids = explode(',', $ids);
                        $param = $this->repository->findMany($ids);
                    }
                    $column->call($param);
                }
            }
        }
    }

    protected function initializeFilters()
    {
        $this->initializeAction();
        foreach ($this->filters() as $filter) {
            $filter->initialize();
            if ($filter->isActive()) {
                $this->activeFilters[] = $filter;
            }
        }
    }

    protected function modifyQuery($query)
    {
        foreach ($this->scope() as $scope) {
            if (! is_null($scope)) {
                $method = array_shift($scope);
                call_user_func_array([
                    $query,
                    $method,
                ], $scope);
            }
        }
        $apply = $this->apply();
        if (! is_null($apply)) {
            call_user_func($apply, $query);
        }
        foreach ($this->activeFilters as $filter) {
            $filter->apply($query);
        }
    }

    /**
     * @param array|null $actions
     *
     * @return $this|array
     */
    public function actions($actions = null)
    {
        if (is_null($actions)) {
            foreach ($this->actions as $action) {
                $action->url($this->model()->displayUrl([
                    '_action' => $action->name(),
                    '_ids'    => '',
                ]));
            }

            return $this->actions;
        }
        $this->actions = $actions;

        return $this;
    }

    /**
     * @param bool|null $controlActive
     *
     * @return $this|bool
     */
    public function controlActive($controlActive = null)
    {
        if (is_null($controlActive)) {
            return $this->controlActive;
        }
        $this->controlActive = $controlActive;

        return $this;
    }

    /**
     * @return $this
     */
    public function enableControls()
    {
        $this->controlActive(true);

        return $this;
    }

    /**
     * @return $this
     */
    public function disableControls()
    {
        $this->controlActive(false);

        return $this;
    }

    public function model()
    {
        return app('sleeping_owl.admin')->getModel($this->class);
    }

    /**
     * @param array|null $parameters
     *
     * @return $this|array
     */
    public function parameters($parameters = null)
    {
        if (is_null($parameters)) {
            return $this->parameters;
        }
        $this->parameters = $parameters;

        return $this;
    }

    /**
     * @return array
     */
    protected function getParams()
    {
        return [
            'title'     => $this->title(),
            'columns'   => $this->allColumns(),
            'creatable' => ! is_null($this->model()->create()),
            'createUrl' => $this->model()->createUrl($this->parameters() + Input::all()),
            'actions'   => $this->actions(),
        ];
    }

    /**
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function render()
    {
        $query = $this->repository->query();
        $this->modifyQuery($query);
        $params = $this->getParams();
        $params['collection'] = $query->get();

        return app('sleeping_owl.template')->view('display.'.$this->view, $params);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->render();
    }
}
