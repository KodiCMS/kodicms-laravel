<?php
namespace KodiCMS\SleepingOwlAdmin;

use Illuminate\View\View;
use KodiCMS\SleepingOwlAdmin\Model\ModelConfiguration;
use KodiCMS\SleepingOwlAdmin\Interfaces\TemplateInterface;

class Admin
{
    /**
     * @var ModelConfiguration[]
     */
    protected $models = [];

    /**
     * @var TemplateInterface
     */
    protected $template;

    /**
     * @return string[]
     */
    public function modelAliases()
    {
        return array_map(function ($model) {
            return $model->alias();
        }, static::models());
    }

    /**
     * @param $class
     *
     * @return ModelConfiguration
     */
    public function getModel($class)
    {
        if ($this->hasModel($class)) {
            return $this->models[$class];
        }

        $model = new ModelConfiguration($class);
        $this->setModel($class, $model);

        return $model;
    }

    /**
     * @return ModelConfiguration[]
     */
    public function getModels()
    {
        return $this->models;
    }

    /**
     * @param $class
     *
     * @return bool
     */
    public function hasModel($class)
    {
        return array_key_exists($class, $this->models);
    }

    /**
     * @param                    $class
     * @param ModelConfiguration $model
     */
    public function setModel($class, $model)
    {
        $this->models[$class] = $model;
    }

    /**
     * @return TemplateInterface
     */
    public function template()
    {
        if (is_null($this->template)) {
            $templateClass  = config('admin.template');
            $this->template = app($templateClass);
        }

        return $this->template;
    }

    /**
     * @param             $content
     * @param string|null $title
     *
     * @return View
     */
    public static function view($content, $title = null)
    {
        $controller = app(\KodiCMS\SleepingOwlAdmin\Http\Controllers\AdminController::class);

        return $controller->render($title, $content);
    }
}
