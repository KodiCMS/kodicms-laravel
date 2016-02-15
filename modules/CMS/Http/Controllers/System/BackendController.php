<?php

namespace KodiCMS\CMS\Http\Controllers\System;

use UI;
use View;
use Meta;
use KodiCMS\Navigation\Navigation;
use KodiCMS\Support\Helpers\Callback;
use KodiCMS\CMS\Exceptions\ValidationException;
use KodiCMS\Support\Helpers\NavigationBreadcrumbs;
use KodiCMS\CMS\Breadcrumbs\Collection as Breadcrumbs;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class BackendController extends TemplateController
{
    /**
     * @var bool
     */
    public $authRequired = true;

    /**
     * @var Navigation
     */
    public $navigation;

    /**
     * @var Breadcrumbs
     */
    public $breadcrumbs;

    public function boot()
    {
        $this->navigation = Navigation::make(config('sitemap', []));
        $this->breadcrumbs = new Breadcrumbs;
    }

    public function initControllerAcl()
    {
        parent::initControllerAcl();
        $this->acl->addPermission($this->getCurrentAction(), $this->getRouter()->currentRouteName());
    }

    public function before()
    {
        $currentPage = $this->navigation->getCurrentPage();

        $this->breadcrumbs->add(UI::icon('home'), route('backend.dashboard'));

        if (! is_null($currentPage)) {
            new NavigationBreadcrumbs($this->breadcrumbs, $currentPage);
        }

        View::share('currentPage', $currentPage);

        parent::before();
    }

    public function after()
    {
        $this->template->with('breadcrumbs', $this->breadcrumbs)
            ->with('navigation', $this->navigation)
            ->with('bodyId', $this->getRouterPath());

        parent::after();
    }

    /**
     * @param string      $title
     * @param string|null $url
     *
     * @return $this
     */
    protected function setTitle($title, $url = null)
    {
        $this->breadcrumbs->add($title, $url);

        return parent::setTitle($title);
    }

    public function registerMedia()
    {
        parent::registerMedia();

        $this->templateScripts['ACE_THEME'] = config('cms.default_ace_theme', 'textmate');
        $this->templateScripts['DEFAULT_HTML_EDITOR'] = config('cms.default_html_editor', '');
        $this->templateScripts['DEFAULT_CODE_EDITOR'] = config('cms.default_code_editor', '');

        Meta::loadPackage('libraries', 'core');
        $this->includeModuleMediaFile($this->getRouterController());
        $this->includeMergedMediaFile('backendEvents', 'js/backendEvents');
    }

    /**
     * Execute an action on the controller.
     *
     * @param  string $method
     * @param  array  $parameters
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function callAction($method, $parameters)
    {
        try {
            return parent::callAction($method, $parameters);
        } catch (ModelNotFoundException $e) {
            $model = $e->getModel();
            if (method_exists($model, 'getNotFoundMessage')) {
                $message = Callback::invoke($model.'@'.'getNotFoundMessage');
            } else {
                $message = $e->getMessage();
            }

            $this->throwFailException(
                $this->smartRedirect()->withErrors($message)
            );
        } catch (ValidationException $e) {
            $this->throwValidationException(
                $this->request, $e->getValidator()
            );
        }
    }
}
