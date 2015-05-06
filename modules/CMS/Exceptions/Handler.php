<?php namespace KodiCMS\CMS\Exceptions;

use KodiCMS\API\Exceptions\Response as APIExceptionResponse;
use KodiCMS\API\Exceptions\Exception as APIException;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Debug\ExceptionHandler as SymfonyDisplayer;

class Handler extends ExceptionHandler {

	/**
	 * A list of the exception types that should not be reported.
	 *
	 * @var array
	 */
	protected $dontReport = [
		'Symfony\Component\HttpKernel\Exception\HttpException'
	];

	/**
	 * Report or log an exception.
	 *
	 * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
	 *
	 * @param  \Exception  $e
	 * @return void
	 */
	public function report(\Exception $e)
	{
		return parent::report($e);
	}

	/**
	 * Render an exception into an HTTP response.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Exception  $e
	 * @return \Illuminate\Http\Response
	 */
	public function render($request, \Exception $e)
	{
		if($request->ajax() OR ($e instanceof APIException)) {
			return (new APIExceptionResponse(config('app.debug')))->createResponse($e);
		}

		// TODO: поправить отлов исключений
		if(config('app.debug') or !$this->isHttpException($e))
		{
			return $this->renderExceptionWithWhoops($e);
		}
		else
		{
			return $this->renderHttpException($e);
		}
	}

	/**
	 * Render the given HttpException.
	 *
	 * @param  \Symfony\Component\HttpKernel\Exception\HttpException  $e
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	protected function renderHttpException(HttpException $e)
	{
		$status = $e->getStatusCode();

		if (view()->exists("csm::errors.{$status}"))
		{
			return response()->view("errors.{$status}", [
				'message' => $e->getMessage(),
				'line' => $e->getLine(),
				'file' => $e->getFile(),
				'code' => $status,
				'bodyId' => 'error.' . $status
			], $status);
		}
		else
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
	protected function renderExceptionWithWhoops(Exception $e)
	{
		$whoops = new \Whoops\Run;
		$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler());

		return new \Illuminate\Http\Response(
			$whoops->handleException($e),
			$e->getStatusCode(),
			$e->getHeaders()
		);
	}
}