<?php

namespace KodiCMS\CMS\Http\Controllers\System;

use App;
use Auth;
use Lang;
use View;
use Assets;
use ModulesFileSystem;
use KodiCMS\Support\Helpers\File;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Contracts\Support\Arrayable;

class TemplateController extends Controller
{
    /**
     * @var  \View  page template
     */
    public $template = 'cms::app.backend';

    /**
     * @var  bool  auto render template
     **/
    public $autoRender = true;

    /**
     * @var bool
     */
    public $onlyContent = false;

    /**
     * @var array
     */
    public $templateScripts = [];

    /**
     * @param string $view
     * @param array  $data
     *
     * @return View
     */
    public function setContent($view, array $data = [])
    {
        if (! is_null($this->template)) {
            $content = view($this->wrapNamespace($view), $data);
            $this->template->with('content', $content);

            return $content;
        }

        return view($this->wrapNamespace($view), $data);
    }

    public function before()
    {
        parent::before();

        if ($this->autoRender === true) {
            $this->registerMedia();
        }

        View::share('controllerAction', $this->getCurrentAction());
        View::share('currentUser', $this->currentUser);
        View::share('requestType', $this->requestType);

        // Todo: подумать нужно ли передавать во view название модуля
        //View::share('currentModule', substr($this->getModuleNamespace(), 0, -2));
    }

    public function after()
    {
        parent::after();

        if ($this->autoRender === true) {
            if ($this->onlyContent) {
                $this->template = $this->template->content;
            } else {
                Assets::group('global', 'templateScripts', '<script type="text/javascript">'.$this->getTemplateScriptsAsString().'</script>', 'global');
            }
        }
    }

    public function registerMedia()
    {
        $this->templateScripts = [
            'CURRENT_URL'       => $this->request->url(),
            'SITE_URL'          => url()->current(),
            'BASE_URL'          => backend_url(),
            'BACKEND_PATH'      => backend_url_segment(),
            'BACKEND_RESOURCES' => resources_url(),
            'PUBLIC_URL'        => url()->current(),
            'LOCALE'            => Lang::getLocale(),
            'ROUTE'             => ! is_null($this->getRouter()) ? $this->getRouter()->currentRouteAction() : null,
            'ROUTE_PATH'        => $this->getRouterPath(),
            'REQUEST_TYPE'      => $this->requestType,
            'USER_ID'           => Auth::id(),
            'MESSAGE_ERRORS'    => view()->shared('errors')->getBag('default'),
            'MESSAGE_SUCCESS'   => (array) $this->session->get('success', []),
        ];
    }

    /**
     * @return string
     */
    public function getTemplateScriptsAsString()
    {
        $script = '';
        foreach ($this->templateScripts as $var => $value) {
            if ($value instanceof Jsonable) {
                $value = $value->toJson();
            } elseif ($value instanceof Arrayable) {
                $value = json_encode($value->toArray());
            } else {
                $value = json_encode($value);
            }

            $script .= "var {$var} = {$value};\n";
        }

        return $script;
    }

    /**
     * @param string $key
     * @param string $file
     */
    public function includeMergedMediaFile($key, $file)
    {
        $mediaContent = '<script type="text/javascript">'.File::mergeByPath($file, 'js').'</script>';
        Assets::group('global', $key, $mediaContent, 'global');
    }

    /**
     * @param $filename
     */
    public function includeModuleMediaFile($filename)
    {
        if (ModulesFileSystem::findFile('resources/js', $filename, 'js')) {
            Assets::addJs('include.'.$filename, backend_resources_url("/js/$filename.js"), 'core', false);
        }
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
        if ($this->autoRender === true) {
            $this->setupLayout();
        }

        $response = parent::callAction($method, $parameters);

        if (is_null($response) && $this->autoRender === true && ! is_null($this->template)) {
            $response = $this->response->setContent($this->template);
        }

        return $response;
    }

    /**
     * Setup the layout used by the controller.
     *
     * @return $this
     */
    protected function setupLayout()
    {
        if (! is_null($this->template)) {
            $this->template = view($this->template);
        }

        return $this;
    }

    /**
     * Set the layout used by the controller.
     *
     * @param $name
     *
     * @return $this
     */
    protected function setLayout($name)
    {
        $this->template = $name;

        return $this;
    }

    /**
     * @param $title
     *
     * @return $this
     */
    protected function setTitle($title)
    {
        // Initialize empty values
        $this->template->with('title', $title);

        return $this;
    }
}
