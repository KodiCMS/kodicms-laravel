<?php namespace KodiCMS\Pages\Exceptions;

use KodiCMS\CMS\Exceptions\Exception;
use KodiCMS\Pages\Model\FrontendPage;
use Request;
use Illuminate\Http\Response;
use KodiCMS\CMS\Helpers\File;

class PageNotFoundException extends Exception
{
	public function __construct($message = "", $code = 0, Exception $previous = null)
	{
		parent::__construct($message, $code, $previous);

		if(config('app.debug')) return;

		$ext = pathinfo(Request::getUri(), PATHINFO_EXTENSION);
		$mimetype = null;

		if (empty($ext) or ($ext and !($mimetype = File::mimeByExt($ext))))
		{
			$mimetype = 'text/html';
		}

		if ($mimetype AND $mimetype != 'text/html')
		{
			$response = new Response();
			$this->sendResponse($response, $mimetype);
		}
		elseif (!is_null($page = FrontendPage::findByField('behavior', 'page.not.found')))
		{
			$controller = app()->make('\KodiCMS\Pages\Http\Controllers\FrontendController');

			$response = app()->call([$controller, 'run'], [$page->getUri()]);
			$this->sendResponse($response, $mimetype);
		}
	}

	/**
	 * @param Response $response
	 * @param string $mimetype
	 */
	protected function sendResponse(Response $response, $mimetype)
	{
		if(empty($mimetype))
		{
			$mimetype = 'text/html';
		}

		$response->header('Content-type', $mimetype);
		$response->setStatusCode(404);
		$response->send();
		exit();
	}
}
