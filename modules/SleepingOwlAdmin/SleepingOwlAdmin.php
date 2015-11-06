<?php

namespace KodiCMS\SleepingOwlAdmin;

use Illuminate\Contracts\Support\Renderable;
use KodiCMS\CMS\Navigation\Section;
use KodiCMS\SleepingOwlAdmin\Model\ModelConfiguration;
use KodiCMS\CMS\Navigation\Section as NavigationSection;
use KodiCMS\SleepingOwlAdmin\Interfaces\TemplateInterface;
use KodiCMS\SleepingOwlAdmin\Http\Controllers\AdminController;

class SleepingOwlAdmin
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
     * @var NavigationPage[]
     */
    protected $menuItems = [];

    /**
     * @return string[]
     */
    public function modelAliases()
    {
        return array_map(function (ModelConfiguration $model) {
            return $model->getAlias();
        }, $this->getModels());
    }

    /**
     * @param string $class
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
     * @param string $class
     *
     * @return bool
     */
    public function hasModel($class)
    {
        return array_key_exists($class, $this->models);
    }

    /**
     * @param string             $class
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
            $templateClass = config('sleeping_owl.template');
            $this->template = app($templateClass);
        }

        return $this->template;
    }

    /**
     * @param string $class
     *
     * @return NavigationPage
     */
    public function addMenuLink($class)
    {
        $model = $this->getModel($class);

        $page = new NavigationPage($model);
        $this->menuItems[] = $page;

        return $page;
    }

    /**
     * @param NavigationSection $navigation
     */
    public function buildMenu(NavigationSection $navigation)
    {
        $section = new Section([
            'name'     => 'SleepingOwl',
            'priority' => 999,
            'icon' => 'cubes',
        ]);

        foreach ($this->menuItems as $item) {
            $section->addPage($item);
        }

        $navigation->addPage($section);
    }

    /**
     * @param string|Renderable $content
     * @param string|null       $title
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function view($content, $title = null)
    {
        $controller = app(AdminController::class);

        return $controller->render($title, $content);
    }
}
