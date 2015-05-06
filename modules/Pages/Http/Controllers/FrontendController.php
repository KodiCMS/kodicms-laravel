<?php namespace KodiCMS\Pages\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use KodiCMS\CMS\Http\Controllers\System\Controller;
use KodiCMS\Pages\Exceptions\LayoutNotFoundException;
use KodiCMS\Pages\Exceptions\PageNotFoundException;
use KodiCMS\Pages\Model\FrontendPage;
use KodiCMS\Widgets\Collection\PageWidgetCollection;

class FrontendController extends Controller
{
	/**
	 * @var Request
	 */
	protected $request;

	// TODO: использовать Dispacher для генерации событий
	public function run(Request $request)
	{
		$this->request = $request;

		$uri = $this->request->path();

		event('frontend.requested', [$uri]);

		$frontPage = FrontendPage::findByUri($uri);

		if ($frontPage instanceof FrontendPage)
		{
			if ($frontPage->isRedirect() AND strlen($frontPage->getRedirectUrl()) > 0)
			{
				return redirect($frontPage->getRedirectUrl(), 301);
			}
			else
			{
				return $this->render($frontPage);
			}
		}

		if (config('cms.find_similar') AND ($uri = FrontendPage::findSimilar($uri)) !== false)
		{
			return redirect($uri, 301);
		}

		event('frontend.not_found', [$uri]);
		throw new PageNotFoundException(trans('pages::core.messages.not_found'));
	}

	/**
	 * TODO: добавить кеширование вывода
	 *
	 * @param FrontendPage $frontPage
	 * @return \Illuminate\View\View|null
	 * @throws LayoutNotFoundException
	 */
	protected function render(FrontendPage $frontPage)
	{
		event('frontend.found', [$frontPage]);

		app()->singleton('frontpage', function () use ($frontPage)
		{
			return $frontPage;
		});

		$layout = $frontPage->getLayoutView();
		if (is_null($layout))
		{
			throw new LayoutNotFoundException(trans('pages::core.messages.layout_not_set'));
		}

		//$widgetCollection = new PageWidgetCollection($frontPage);

		$html = (string)$frontPage->getLayoutView();
		if (auth()->check() AND auth()->user()->hasRole(['administrator', 'developer']))
		{
			$injectHTML = (string) view('cms::app.partials.toolbar');
			// Insert system HTML before closed tag body
			$matches = preg_split('/(<\/body>)/i', $html, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);

			if (count($matches) > 1)
			{
				/* assemble the HTML output back with the iframe code in it */
				$html = $matches[0] . $injectHTML . $matches[1] . $matches[2];
			}
		}

		$response = new Response();

		$response->header('Content-Type', $frontPage->getMime());

		if (config('cms.show_response_sign', true))
		{
			$response->header('X-Powered-CMS', \CMS::NAME . '/' . \CMS::VERSION);
		}

		$response->setContent($html);

		// Set the ETag header
		$response->setEtag(sha1($html));

		$response->setLastModified($frontPage->getCreatedAt());

		// mark the response as either public or private
		$response->setPublic();

		// Check that the Response is not modified for the given Request
		if ($response->isNotModified($this->request))
		{
			// return the 304 Response immediately
			return $response;
		}

		return $response;
	}
}