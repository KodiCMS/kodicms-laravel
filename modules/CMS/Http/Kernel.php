<?php

namespace KodiCMS\CMS\Http;

use KodiCMS\Support\Helpers\Profiler;
use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * @var array
     */
    protected $middleware = [
        \Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class,
        \KodiCMS\CMS\Http\Middleware\EncryptCookies::class,
        \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
        \Illuminate\Session\Middleware\StartSession::class,
        \Illuminate\View\Middleware\ShareErrorsFromSession::class,
        \KodiCMS\CMS\Http\Middleware\VerifyCsrfToken::class,
    ];

    /**
     * The application's route middleware.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'backend.auth'  => \KodiCMS\Users\Http\Middleware\BackendAuthenticate::class,
        'backend.guest' => \KodiCMS\Users\Http\Middleware\BackendRedirectIfAuthenticated::class,
        'auth'          => \KodiCMS\Users\Http\Middleware\Authenticate::class,
        'guest'         => \KodiCMS\Users\Http\Middleware\RedirectIfAuthenticated::class,
    ];

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
