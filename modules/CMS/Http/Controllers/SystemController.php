<?php namespace KodiCMS\CMS\Http\Controllers;

class SystemController extends System\BackendController {

	public function settings()
	{

	}

	public function about()
	{
		$this->setContent('system.about');
	}

	public function phpInfo()
	{
		$this->autoRender = FALSE;

		phpinfo();
	}
}
