<?php

namespace KodiCMS\CMS\Http;

use Illuminate\Routing\Router;
use KodiCMS\Support\Helpers\Profiler;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * Create a new HTTP kernel instance.
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    public function __construct(Application $app, Router $router)
    {
        $this->middleware[] = \KodiCMS\CMS\Http\Middleware\PostJson::class;

        $this->routeMiddleware['backend.auth'] = \App\Http\Middleware\Authenticate::class;
        $this->routeMiddleware['backend.guest'] = \App\Http\Middleware\RedirectIfAuthenticated::class;

        parent::__construct($app, $router);
    }

    /**
     * Bootstrap the application for HTTP requests.
     *
     * @return void
     */
    public function bootstrap()
    {
        foreach ($this->bootstrappers() as $bootstrapper) {
            $this->app['events']->listen('bootstrapping: '.$bootstrapper, function () use ($bootstrapper) {
                Profiler::start('HttpKernel', $bootstrapper, md5($bootstrapper));
            });

            $this->app['events']->listen('bootstrapped: '.$bootstrapper, function () use ($bootstrapper) {
                Profiler::stop(md5($bootstrapper));
            });
        }

        parent::bootstrap();
    }

    /**
     * Send the given request through the middleware / router.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    protected function sendRequestThroughRouter($request)
    {
        $token = Profiler::start('Request', $request->path());

        $response = parent::sendRequestThroughRouter($request);

        Profiler::stop($token);

        return $response;
    }
}
