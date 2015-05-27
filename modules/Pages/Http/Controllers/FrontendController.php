<?php namespace KodiCMS\Pages\Http\Controllers;

use KodiCMS\Pages\Model\FrontendPage;
use KodiCMS\Pages\Exceptions\PageNotFoundException;
use KodiCMS\Pages\Http\Controllers\System\FrontPageController;

class FrontendController extends FrontPageController
{
	public function run($slug)
	{
		event('frontend.requested', [$slug]);

		$frontPage = FrontendPage::findByUri($slug);

		if ($frontPage instanceof FrontendPage)
		{
			if ($frontPage->isRedirect() AND strlen($frontPage->getRedirectUrl()) > 0)
			{
				return redirect($frontPage->getRedirectUrl(), 301);
			}
			else
			{
				event('frontend.found', [$frontPage]);

				$layout = $frontPage->getLayoutView();

				return $this->render($layout, $frontPage->getMime());
			}
		}

		if (config('cms.find_similar') AND ($uri = FrontendPage::findSimilar($slug)) !== false)
		{
			return redirect($uri, 301);
		}

		event('frontend.not_found', [$slug]);
		throw new PageNotFoundException(trans('pages::core.messages.not_found'));
	}
}