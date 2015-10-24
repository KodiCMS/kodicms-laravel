<?php namespace KodiCMS\CMS\Exceptions;

use App;
use Illuminate\Http\Response;
use KodiCMS\API\Http\Response as APIResponse;
use KodiCMS\CMS\Http\Controllers\ErrorController;
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
		HttpException::class,
		ModelNotFoundException::class,
	];

	/**
	 * Report or log an exception.
	 *
	 * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
	 *
	 * @param  \Exception $e
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
	 * @param  \Exception $e
	 * @return \Illuminate\Http\Response
	 */
	public function render($request, \Exception $e)
	{
		if ($request->ajax() OR ($e instanceof APIException))
		{
			return (new APIResponse(config('app.debug')))->createExceptionResponse($e);
		}

		if ($e instanceof ModelNotFoundException)
		{
			$e = new NotFoundHttpException($e->getMessage(), $e);
		}

		if (config('app.debug') or !App::installed())
		{
			return $this->renderExceptionWithWhoops($e);
		}
		else if (!$this->isHttpException($e))
		{
			return $this->renderException($e);
		}

		return $this->renderHttpException($e);
	}

	/**
	 * Render the given HttpException.
	 *
	 * @param  \Symfony\Component\HttpKernel\Exception\HttpException $e
	 * @return \Illuminate\Http\Response
	 */
	protected function renderHttpException(HttpException $e)
	{
		return $this->renderControllerException($e, $e->getStatusCode());
	}

	/**
	 * @param  \Exception $e
	 * @return \Illuminate\Http\Response
	 */
	protected function renderException(\Exception $e)
	{
		return $this->renderControllerException($e, 500);
	}

	/**
	 * Render an exception using ErrorController
	 *
	 * @param  \Exception $e
	 * @return \Illuminate\Http\Response
	 */
	protected function renderControllerException(\Exception $e, $code = 500)
	{
		try
		{
			$controller = app()->make(ErrorController::class);
			if (method_exists($controller, 'error' . $code))
			{
				$action = 'error' . $code;
			}
			else
			{
				$action = 'error500';
			}

			return $this->toIlluminateResponse(new Response($controller->callAction($action, [$e])), $e);
		}
		catch (\Exception $ex)
		{
			return $this->convertExceptionToResponse($ex);
		}
	}

	/**
	 * Render an exception using Whoops.
	 *
	 * @param  \Exception $e
	 * @return \Illuminate\Http\Response
	 */
	protected function renderExceptionWithWhoops(\Exception $e)
	{
		$whoops = new \Whoops\Run;
		$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);

		return $this->toIlluminateResponse(new Response($whoops->handleException($e)), $e);
	}
}