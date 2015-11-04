<?php

namespace KodiCMS\Pages\Behavior;

class Router
{
    // Matches a URI group and captures the contents
    const REGEX_GROUP = '\(((?:(?>[^()]+)|(?R))*)\)';

    // Defines the pattern of a <segment>
    const REGEX_KEY = '<([a-zA-Z0-9_]++)>';

    // What can be part of a <segment> value
    const REGEX_SEGMENT = '[^/.,;?\n]++';

    // What must be escaped in the route regex
    const REGEX_ESCAPE = '[.\\+*?[^\\]${}=!|]';

    /**
     * @var  array
     */
    protected $routes = [];

    /**
     * @var array
     */
    protected $parameters = [];

    /**
     * @var string
     */
    protected $matchedRoute = null;

    /**
     * @var null|string
     */
    protected $uri = null;

    /**
     * @param array $routes
     */
    public function __construct(array $routes)
    {
        foreach ($routes as $route => $params) {
            if (! array_key_exists('type', $params)) {
                $routes[$route]['type'] = BehaviorAbstract::ROUTE_TYPE_DEFAULT;
            }

            if (! array_key_exists('method', $params)) {
                $routes[$route]['method'] = $this->getDefaultMethod();
            }
        }

        $this->routes = $routes;
    }

    /**
     * @return array
     */
    public function getRoutes()
    {
        return $this->routes;
    }

    /**
     * @return string
     */
    public function getMatchedRoute()
    {
        return $this->matchedRoute;
    }

    /**
     * @return null|string
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * @param string $name
     * @param mixed  $default
     *
     * @return string|null
     */
    public function getParameter($name, $default = null)
    {
        return array_get($this->parameters, $name, $default);
    }

    /**
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * @return string
     */
    public function getDefaultMethod()
    {
        return 'stub';
    }

    /**
     * @param $uri
     *
     * @return string
     */
    public function findRouteByUri($uri)
    {
        $this->uri = $uri;
        $method = $this->matchRoute($uri);

        return $method;
    }

    /**
     * @param $uri
     *
     * @return string
     */
    final protected function matchRoute($uri)
    {
        foreach ($this->getRoutes() as $_uri => $params) {
            if (! isset($params['method'])) {
                $params['method'] = $this->getDefaultMethod();
            }

            $expression = $this->compileRoute($_uri, array_get($params, 'regex'));
            if (! preg_match($expression, $uri, $matches)) {
                continue;
            }

            foreach ($matches as $key => $value) {
                if (is_int($key)) {
                    // Skip all unnamed keys
                    continue;
                }

                // Set the value for all matched keys
                $this->parameters[$key] = $value;
            }

            $this->matchedRoute = $_uri;

            return $params['method'];
        }

        $this->parameters = preg_split('/\//', $uri, -1, PREG_SPLIT_NO_EMPTY);

        return;
    }

    /**
     * Returns the compiled regular expression for the route. This translates
     * keys and optional groups to a proper PCRE regular expression.
     *
     * @return  string
     * @uses    static::REGEX_ESCAPE
     * @uses    static::REGEX_SEGMENT
     */
    final protected function compileRoute($uri, array $regex = null)
    {
        // The URI should be considered literal except for keys and optional parts
        // Escape everything preg_quote would escape except for : ( ) < >
        $expression = preg_replace('#'.static::REGEX_ESCAPE.'#', '\\\\$0', $uri);

        if (strpos($expression, '(') !== false) {
            // Make optional parts of the URI non-capturing and optional
            $expression = str_replace(['(', ')'], ['(?:', ')?'], $expression);
        }

        // Insert default regex for keys
        $expression = str_replace(['<', '>'], ['(?P<', '>'.static::REGEX_SEGMENT.')'], $expression);

        if ($regex) {
            $search = $replace = [];
            foreach ($regex as $key => $value) {
                $search[] = "<$key>".static::REGEX_SEGMENT;
                $replace[] = "<$key>$value";
            }

            // Replace the default regex with the user-specified regex
            $expression = str_replace($search, $replace, $expression);
        }

        return '#^'.$expression.'$#uD';
    }
}
