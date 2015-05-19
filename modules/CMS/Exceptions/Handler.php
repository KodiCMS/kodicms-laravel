<?php namespace KodiCMS\CMS\Exceptions;

use CMS;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Response;
use KodiCMS\API\Exceptions\Exception as APIException;
use KodiCMS\API\Exceptions\Response as APIExceptionResponse;
use Symfony\Component\Debug\ExceptionHandler as SymfonyDisplayer;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Handler extends ExceptionHandler
{

	/**
	 * A list of the exception types that should not be reported.
	 *
	 * @var array
	 */
	protected $dontReport = ['Symfony\Component\HttpKernel\Exception\HttpException'];

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
			return (new APIExceptionResponse(config('app.debug')))->createResponse($e);
		}

		if (config('app.debug') or !CMS::isInstalled())
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
			$controller = app()->make('\KodiCMS\CMS\Http\Controllers\ErrorController');
			if (method_exists($controller, 'error' . $code))
			{
				$action = 'error' . $code;
			}
			else
			{
				$action = 'error500';
			}

			return new Response($controller->callAction($action, [$e]));
		}
		catch (\Exception $ex)
		{
			return (new SymfonyDisplayer(config('app.debug')))->createResponse($e);
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
		$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler());

		return new Response($whoops->handleException($e), $e->getStatusCode(), $e->getHeaders());
	}
}