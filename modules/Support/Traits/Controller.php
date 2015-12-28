<?php

namespace KodiCMS\Support\Traits;

use Auth;
use ModulesFileSystem;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Session\Store as SessionStore;

trait Controller
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * @var string
     */
    protected $requestType = 'GET';

    /**
     * @var Response
     */
    protected $response;

    /**
     * @var Session
     */
    protected $session;

    /**
     * @var string|null
     */
    public $moduleNamespace = null;

    public function __construct()
    {
        app()->call([$this, 'initController']);

        $this->initControllerAcl();

        // Execute method boot() on controller execute
        if (method_exists($this, 'boot')) {
            app()->call([$this, 'boot']);
        }

        $this->initMiddleware();
    }

    public function initMiddleware()
    {
    }

    /**
     * Execute before an action executed
     * return void.
     */
    public function before()
    {
    }

    /**
     * Execute after an action executed
     * return void.
     */
    public function after()
    {
    }

    /**
     * @param Request      $request
     * @param Response     $response
     * @param SessionStore $session
     */
    public function initController(Request $request, Response $response, SessionStore $session)
    {
        $this->request = $request;
        $this->response = $response;
        $this->session = $session;

        $this->requestType = $this->request->input('type', $this->request->method());

        $this->currentUser = Auth::user();
    }

    /**
     * @param string $separator
     *
     * @return string|null
     */
    public function getRouterPath($separator = '.')
    {
        if (! is_null($this->getRouter())) {
            $controller = $this->getRouter()->currentRouteAction();
            $namespace = array_get($this->getRouter()->getCurrentRoute()->getAction(), 'namespace');
            $path = trim(str_replace($namespace, '', $controller), '\\');

            return str_replace(['\\', '@', '..', '.controller.'], $separator, Str::snake($path, '.'));
        }

        return;
    }

    /**
     * @return string
     */
    public function getRouterController()
    {
        return last(explode('\\', get_called_class()));
    }

    /**
     * @return string
     */
    public function getCurrentAction()
    {
        if (! is_null($this->getRouter()) and ! is_null($this->getRouter()->currentRouteAction())) {
            list($class, $method) = explode('@', $this->getRouter()->currentRouteAction(), 2);
        } else {
            $method = null;
        }

        return $method;
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
        $this->before();
        $response = call_user_func_array([$this, $method], $parameters);
        $this->after($response);

        return $response;
    }

    /**
     * @return string
     */
    protected function getModuleNamespace()
    {
        if (is_null($this->moduleNamespace)) {
            return ModulesFileSystem::getModuleNameByNamespace().'::';
        }

        return $this->moduleNamespace;
    }

    /**
     * @param string $string
     *
     * @return string
     */
    protected function wrapNamespace($string)
    {
        if (strpos($string, '::') === false) {
            $string = $this->getModuleNamespace().$string;
        }

        return $string;
    }
}
