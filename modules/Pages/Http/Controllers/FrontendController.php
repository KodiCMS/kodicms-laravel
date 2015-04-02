<?php namespace KodiCMS\Pages\Http\Controllers;

use Illuminate\Support\Facades\Request;
use KodiCMS\CMS\Http\Controllers\Controller;
use KodiCMS\Pages\Model\FrontendPage;

class FrontendController extends Controller {

	public function run()
	{
		$frontPage = FrontendPage::find(Request::path());

		dd($frontPage->getAnchor());
	}
}
