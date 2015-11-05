<?php

namespace KodiCMS\SleepingOwlAdmin\Display;

use Meta;
use Input;
use Route;
use Illuminate\Contracts\Support\Renderable;
use KodiCMS\SleepingOwlAdmin\Columns\Column;
use KodiCMS\SleepingOwlAdmin\Repository\TreeRepository;
use KodiCMS\SleepingOwlAdmin\Interfaces\DisplayInterface;
use KodiCMS\SleepingOwlAdmin\Interfaces\WithRoutesInterface;

class DisplayTree implements Renderable, DisplayInterface, WithRoutesInterface
{
    /**
     * @var string
     */
    protected $class;

    /**
     * @var array
     */
    protected $with = [];

    /**
     * @var TreeRepository
     */
    protected $repository;

    /**
     * @var bool
     */
    protected $reorderable = true;

    /**
     * @var array
     */
    protected $parameters = [];

    /**
     * @var string
     */
    protected $value = 'title';

    /**
     * @var string
     */
    protected $parentField = 'parent_id';

    /**
     * @var string
     */
    protected $orderField = 'order';

    protected $rootParentId = null;

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
     * @param mixed|ull $value
     *
     * @return $this|string
     */
    public function value($value = null)
    {
        if (is_null($value)) {
            return $this->value;
        }
        $this->value = $value;

        return $this;
    }

    /**
     * @param string|null $parentField
     *
     * @return $this|string
     */
    public function parentField($parentField = null)
    {
        if (is_null($parentField)) {
            return $this->parentField;
        }
        $this->parentField = $parentField;

        return $this;
    }

    /**
     * @param string|null $orderField
     *
     * @return $this|string
     */
    public function orderField($orderField = null)
    {
        if (is_null($orderField)) {
            return $this->orderField;
        }
        $this->orderField = $orderField;

        return $this;
    }

    public function rootParentId($rootParentId = null)
    {
        if (func_num_args() == 0) {
            return $this->rootParentId;
        }
        $this->rootParentId = $rootParentId;

        return $this;
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

    public function initialize()
    {
        Meta::loadPackage(get_class());

        $this->repository = new TreeRepository($this->class);
        $this->repository->with($this->with());

        Column::treeControl()->initialize();
    }

    public function reorderable($reorderable = null)
    {
        if (is_null($reorderable)) {
            return $this->reorderable;
        }
        $this->reorderable = $reorderable;

        return $this;
    }

    public function repository()
    {
        $this->repository->parentField($this->parentField());
        $this->repository->orderField($this->orderField());
        $this->repository->rootParentId($this->rootParentId());

        return $this->repository;
    }

    public function parameters($parameters = null)
    {
        if (is_null($parameters)) {
            return $this->parameters;
        }
        $this->parameters = $parameters;

        return $this;
    }

    public function model()
    {
        return app('sleeping_owl.admin')->getModel($this->class);
    }

    /**
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function render()
    {
        return app('sleeping_owl.template')->view('display.tree', [
            'items'       => $this->repository()->tree(),
            'reorderable' => $this->reorderable(),
            'url'         => $this->model()->displayUrl(),
            'value'       => $this->value(),
            'creatable'   => ! is_null($this->model()->create()),
            'createUrl'   => $this->model()->createUrl($this->parameters() + Input::all()),
            'controls'    => [Column::treeControl()],
        ]);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->render();
    }

    public static function registerRoutes()
    {
        Route::post('{adminModel}/reorder', function ($model) {
            $data = Input::get('data');
            $model->display()->repository()->reorder($data);
        });
    }
}
