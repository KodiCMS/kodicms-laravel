<?php namespace KodiCMS\CMS\Http\Controllers;

use Date;
use KodiCMS\CMS\Helpers\Updater;
use WYSIWYG;
use KodiCMS\Support\Helpers\Locale;

class SystemController extends System\BackendController {

	public function settings()
	{
		$htmlEditors = WYSIWYG::htmlSelect(WYSIWYG::html());
		$codeEditors = WYSIWYG::htmlSelect(WYSIWYG::code());
		$dateFormats = Date::getFormats();

		// TODO: сделать вывод языков в нормальном формате
		$availableLocales = Locale::getAvailable();

		$this->setContent('system.settings', compact('htmlEditors', 'codeEditors', 'dateFormats', 'availableLocales'));
	}

	public function about()
	{
		$this->setContent('system.about');
	}

	public function phpInfo()
	{
		$this->autoRender = false;

		phpinfo();
	}

	public function update()
	{
		$updater = new Updater();
		$repositoryVersion = $updater->getRemoteVersion();
		$hasNewVersion = $updater->hasNewVersion();

		$this->setContent('system.update', compact('repositoryVersion', 'hasNewVersion'));
	}
}
