<?php namespace KodiCMS\Pages\Http\Controllers;

use Illuminate\Routing\Route;
use KodiCMS\CMS\Http\Controllers\Controller;
use KodiCMS\Pages\Model\FrontendPage;

class FrontendController extends Controller {

	public function run(Route $router)
	{
		$uri = $router->getParameter('slug');

		FrontendPage::find($uri);
	}
}
