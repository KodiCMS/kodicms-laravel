<?php

namespace KodiCMS\SleepingOwlAdmin\Form;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\View\View;
use Input;
use KodiCMS\SleepingOwlAdmin\Admin;
use KodiCMS\SleepingOwlAdmin\Interfaces\DisplayInterface;
use KodiCMS\SleepingOwlAdmin\Interfaces\FormInterface;
use KodiCMS\SleepingOwlAdmin\Interfaces\FormItemInterface;
use KodiCMS\SleepingOwlAdmin\Model\ModelConfiguration;
use KodiCMS\SleepingOwlAdmin\Repository\BaseRepository;
use URL;
use Validator;

class FormDefault implements Renderable, DisplayInterface, FormInterface
{
    /**
     * View to render.
     * @var string
     */
    protected $view = 'default';

    /**
     * Form related class.
     * @var string
     */
    protected $class;

    /**
     * Form related repository.
     * @var BaseRepository
     */
    protected $repository;

    /**
     * Form items.
     * @var FormItemInterface[]
     */
    protected $items = [];

    /**
     * Form action url.
     * @var string
     */
    protected $action;

    /**
     * Form related model instance.
     * @var mixed
     */
    protected $instance;

    /**
     * Currently loaded model id.
     * @var int
     */
    protected $id;

    /**
     * Is form already initialized?
     * @var bool
     */
    protected $initialized = false;

    /**
     * Initialize form.
     */
    public function initialize()
    {
        if ($this->initialized) {
            return;
        }

        $this->initialized = true;
        $this->repository = new BaseRepository($this->class);
        $this->instance(app($this->class));
        $items = $this->items();
        array_walk_recursive($items, function ($item) {
            if ($item instanceof FormItemInterface) {
                $item->initialize();
            }
        });
    }

    /**
     * Set form action.
     *
     * @param string $action
     */
    public function setAction($action)
    {
        if (is_null($this->action)) {
            $this->action = $action;
        }
    }

    /**
     * Set form class.
     *
     * @param string $class
     */
    public function setClass($class)
    {
        if (is_null($this->class)) {
            $this->class = $class;
        }
    }

    /**
     * Get or set form items.
     *
     * @param FormInterface[]|null $items
     *
     * @return $this|FormInterface[]
     */
    public function items($items = null)
    {
        if (is_null($items)) {
            return $this->items;
        }
        $this->items = $items;

        return $this;
    }

    /**
     * Get or set form related model instance.
     *
     * @param mixed|null $instance
     *
     * @return $this|mixed
     */
    public function instance($instance = null)
    {
        if (is_null($instance)) {
            return $this->instance;
        }
        $this->instance = $instance;
        $items = $this->items();
        array_walk_recursive($items, function ($item) use ($instance) {
            if ($item instanceof FormItemInterface) {
                $item->setInstance($instance);
            }
        });

        return $this;
    }

    /**
     * Set currently loaded model id.
     *
     * @param int $id
     */
    public function setId($id)
    {
        if (is_null($this->id)) {
            $this->id = $id;
            $this->instance($this->repository->find($id));
        }
    }

    /**
     * Get related form model configuration.
     * @return ModelConfiguration
     */
    public function model()
    {
        return app('sleeping_owl.admin')->getModel($this->class);
    }

    /**
     * Save instance.
     *
     * @param $model
     */
    public function save($model)
    {
        if ($this->model() != $model) {
            return;
        }
        $items = $this->items();
        array_walk_recursive($items, function ($item) {
            if ($item instanceof FormItemInterface) {
                $item->save();
            }
        });
        $this->instance()->save();
    }

    /**
     * Validate data, returns null on success.
     *
     * @param mixed $model
     *
     * @return Validator|null
     */
    public function validate($model)
    {
        if ($this->model() != $model) {
            return;
        }

        $rules = [];
        $items = $this->items();
        array_walk_recursive($items, function ($item) use (&$rules) {
            if ($item instanceof FormItemInterface) {
                $rules += $item->getValidationRules();
            }
        });
        $data = Input::all();
        $verifier = app('validation.presence');
        $verifier->setConnection($this->instance()->getConnectionName());
        $validator = Validator::make($data, $rules);
        $validator->setPresenceVerifier($verifier);
        if ($validator->fails()) {
            return $validator;
        }

        return;
    }

    /**
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function render()
    {
        return app('sleeping_owl.template')->view('form.'.$this->view, [
            'items'    => $this->items(),
            'instance' => $this->instance(),
            'action'   => $this->action,
            'backUrl'  => session('_redirectBack', URL::previous()),
        ]);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->render();
    }
}
