<?php namespace KodiCMS\Pages\Http\Controllers;

use Illuminate\Support\Facades\Request;
use KodiCMS\CMS\Http\Controllers\System\Controller;
use KodiCMS\Pages\Exceptions\LayoutNotFoundException;
use KodiCMS\Pages\Exceptions\PageNotFoundException;
use KodiCMS\Pages\Model\FrontendPage;

class FrontendController extends Controller
{
	public function run()
	{
		$uri = Request::path();
		event('frontend.requested', [$uri]);

		$frontPage = FrontendPage::find(Request::path());

		if ($frontPage instanceof FrontendPage) {
			if ($frontPage->isRedirect() AND strlen($frontPage->getRedirectUrl()) > 0) {
				return redirect($frontPage->getRedirectUrl(), 301);
			} else {
				event('frontend.found', [$frontPage]);
				return $this->render($frontPage);
			}
		} else {
			if (config('cms.find_similar')) {
				if (($uri = FrontendPage::findSimilar($uri)) !== FALSE) {
					return redirect($uri, 301);
				}
			}
		}

		throw new PageNotFoundException;
	}

	/**
	 * TODO: добавить кеширование вывода, добавтить инициализацию Context
	 * 
	 * @param FrontendPage $frontPage
	 * @return \Illuminate\View\View|null
	 * @throws LayoutNotFoundException
	 */
	protected function render(FrontendPage $frontPage)
	{
		app()->singleton('frontpage', function() use($frontPage) {
			return $frontPage;
		});

		$layout = $frontPage->getLayoutView();
		if (is_null($layout)) {
			throw new LayoutNotFoundException;
		}

		return $frontPage->getLayoutView();
	}
}
