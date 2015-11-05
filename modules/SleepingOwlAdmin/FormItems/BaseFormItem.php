<?php

namespace KodiCMS\SleepingOwlAdmin\FormItems;

use Meta;
use Illuminate\Contracts\Support\Renderable;
use KodiCMS\SleepingOwlAdmin\Interfaces\FormItemInterface;

abstract class BaseFormItem implements Renderable, FormItemInterface
{
    /**
     * @var string
     */
    protected $view;

    protected $instance;

    /**
     * @var array
     */
    protected $validationRules = [];

    public function initialize()
    {
        Meta::loadPackage(get_class());
    }

    public function setInstance($instance)
    {
        return $this->instance($instance);
    }

    public function instance($instance = null)
    {
        if (is_null($instance)) {
            return $this->instance;
        }
        $this->instance = $instance;

        return $this;
    }

    /**
     * @param array|string|null $validationRules
     *
     * @return $this|array
     */
    public function validationRules($validationRules = null)
    {
        if (is_null($validationRules)) {
            return $this->validationRules;
        }
        if (is_string($validationRules)) {
            $validationRules = explode('|', $validationRules);
        }
        $this->validationRules = $validationRules;

        return $this;
    }

    /**
     * @return array
     */
    public function getValidationRules()
    {
        return $this->validationRules();
    }

    /**
     * @param string $rule
     *
     * @return $this
     */
    public function validationRule($rule)
    {
        $this->validationRules[] = $rule;

        return $this;
    }

    public function save()
    {
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return [
            'instance' => $this->instance(),
        ];
    }

    /**
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function render()
    {
        return app('sleeping_owl.template')->view('formitem.'.$this->view, $this->getParams())->render();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->render();
    }
}
