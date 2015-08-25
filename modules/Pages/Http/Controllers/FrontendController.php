<?php namespace KodiCMS\Pages\Http\Controllers;

use KodiCMS\Pages\Model\FrontendPage;
use KodiCMS\Pages\Exceptions\PageNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use KodiCMS\Pages\Http\Controllers\System\FrontPageController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class FrontendController extends FrontPageController
{
	public function run($slug)
	{
		event('frontend.requested', [$slug]);

		$frontPage = FrontendPage::findByUri($slug);
		$notFoundMessage = trans('pages::core.messages.not_found');

		if ($frontPage instanceof FrontendPage)
		{
			if ($frontPage->isRedirect() AND strlen($frontPage->getRedirectUrl()) > 0)
			{
				return redirect($frontPage->getRedirectUrl(), 301);
			}
			else
			{
				try
				{
					event('frontend.found', [$frontPage]);

					$layout = $frontPage->getLayoutView();

					return $this->render($layout, $frontPage->getMime());
				}
				catch (NotFoundHttpException $e)
				{
					$notFoundMessage = $e->getMessage();
				}
				catch(HttpException $e)
				{
					$notFoundMessage = $e->getMessage();
				}

			}
		}

		if (config('cms.find_similar') AND ($uri = FrontendPage::findSimilar($slug)) !== false)
		{
			return redirect($uri, 301);
		}

		event('frontend.not_found', [$slug]);
		throw new PageNotFoundException($notFoundMessage);
	}
}