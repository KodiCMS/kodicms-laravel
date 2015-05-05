<?php namespace KodiCMS\Installer\Http\Controllers;

use Illuminate\Auth\Guard;
use KodiCMS\CMS\Http\Controllers\System\FrontendController;
use KodiCMS\Installer\Installer;

class InstallerController extends FrontendController {

	protected function loadCurrentUser(Guard $auth)
	{
		$this->currentUser = NULL;
	}

	public function boot()
	{
		$this->installer = new Installer();
	}

	public function run()
	{
		dd('sdfdsf');
	}
}