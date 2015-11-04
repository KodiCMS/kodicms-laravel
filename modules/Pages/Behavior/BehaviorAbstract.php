<?php

namespace KodiCMS\Pages\Behavior;

use KodiCMS\Support\Helpers\Callback;
use KodiCMS\Pages\Model\FrontendPage;
use KodiCMS\Pages\Contracts\BehaviorInterface;
use KodiCMS\Pages\Exceptions\BehaviorException;
use KodiCMS\Pages\Contracts\BehaviorPageInterface;

abstract class BehaviorAbstract implements BehaviorInterface
{
    const ROUTE_TYPE_DEFAULT = 'default';
    const ROUTE_TYPE_PAGE = 'page';
    const ROUTE_TYPE_CUSTOM = 'custom';

    /**
     * @var Router
     */
    protected $router;

    /**
     * @var FrontendPage
     */
    protected $page = null;

    /**
     * @var array
     */
    protected $parameters = [];

    /**
     * @var Settings
     */
    protected $settings;

    /**
     * @var string
     */
    protected $settingsClass = Settings::class;

    /**
     * @var null|string
     */
    protected $settingsTemplate = null;

    /**
     * @param array $parameters
     */
    public function __construct(array $parameters = [])
    {
        $this->parameters = $parameters;

        $routes = $this->routeList();
        if (isset($parameters['routes']) and is_array($parameters['routes'])) {
            $routes = $parameters['routes'] + $routes;
        }

        $settingsClass = $this->settingsClass;

        $this->settings = new $settingsClass($this);
        $this->router = new Router($routes);
    }

    /**
     * @param BehaviorPageInterface $page
     *
     * @throws BehaviorException
     */
    public function setPage(BehaviorPageInterface &$page)
    {
        if (! is_null($this->page)) {
            throw new BehaviorException('You can\'t change behavior page');
        }

        $this->page = &$page;
        $this->settings->setSettings($page->getBehaviorSettings());
    }

    /**
     * @return FrontendPage
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * @return array
     */
    public function routeList()
    {
        return [];
    }

    /**
     * @return Router
     */
    public function getRouter()
    {
        return $this->router;
    }

    /**
     * @param string $uri
     *
     * @return string
     */
    public function executeRoute($uri)
    {
        if (empty($uri)) {
            return;
        }

        if (is_null($method = $this->getRouter()->findRouteByUri($uri))) {
            $this->page = FrontendPage::findByUri($uri, $this->page);

            return;
        }

        if (strpos($method, '::') !== false) {
            Callback::invoke($method, [$this]);
        } else {
            $this->{$method}();
        }

        return $method;
    }

    /**
     * @return null|string
     */
    public function getSettingsTemplate()
    {
        return $this->settingsTemplate;
    }

    /**
     * @return Settings
     */
    public function getSettings()
    {
        return $this->settings;
    }

    final public function stub()
    {
    }
}
