<?php

namespace KodiCMS\CMS\Exceptions;

use Illuminate\Http\Response;
use KodiCMS\API\Http\Response as APIResponse;
use KodiCMS\CMS\Http\Controllers\ErrorController;
use Illuminate\Auth\Access\AuthorizationException;
use KodiCMS\API\Exceptions\Exception as APIException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception $e
     *
     * @return void
     */
    public function report(\Exception $e)
    {
        return parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Exception               $e
     *
     * @return \Illuminate\Http\Response
     */
    public function render($request, \Exception $e)
    {
        if ($request->ajax() or ($e instanceof APIException)) {
            return $this->renderApiException($e);
        }

        if ($e instanceof ModelNotFoundException) {
            $e = new NotFoundHttpException($e->getMessage(), $e);
        }

        if (config('app.debug') or ! cms_installed()) {
            return $this->renderExceptionWithWhoops($e);
        } elseif (! $this->isHttpException($e)) {
            return $this->renderException($e);
        }

        return $this->renderHttpException($e);
    }

    /**
     * @param Exception $e
     *
     * @return APIResponse
     */
    protected function renderApiException(Exception $e)
    {
        return (new APIResponse(config('app.debug')))->createExceptionResponse($e);
    }

    /**
     * Render the given HttpException.
     *
     * @param  \Symfony\Component\HttpKernel\Exception\HttpException $e
     *
     * @return \Illuminate\Http\Response
     */
    protected function renderHttpException(HttpException $e)
    {
        return $this->renderControllerException($e, $e->getStatusCode());
    }

    /**
     * @param  \Exception $e
     *
     * @return \Illuminate\Http\Response
     */
    protected function renderException(\Exception $e)
    {
        return $this->renderControllerException($e, 500);
    }

    /**
     * Render an exception using ErrorController.
     *
     * @param  \Exception $e
     * @param  int        $code
     *
     * @return \Illuminate\Http\Response
     */
    protected function renderControllerException(\Exception $e, $code = 500)
    {
        try {
            $controller = app()->make(ErrorController::class);

            if (method_exists($controller, 'error'.$code)) {
                $action = 'error'.$code;
            } else {
                $action = 'errorDefault';
            }

            $response = $controller->callAction($action, [$e]);

            if (! ($response instanceof Response)) {
                $response = new Response($response);
            }

            return $this->toIlluminateResponse($response);
        } catch (\Exception $ex) {
            return $this->convertExceptionToResponse($ex);
        }
    }

    /**
     * Render an exception using Whoops.
     *
     * @param  \Exception $e
     *
     * @return \Illuminate\Http\Response
     */
    protected function renderExceptionWithWhoops(\Exception $e)
    {
        $whoops = new \Whoops\Run;
        $whoops->pushHandler(
            new \Whoops\Handler\PrettyPageHandler
        );

        return $this->toIlluminateResponse(
            new Response($whoops->handleException($e)), $e
        );
    }
}
