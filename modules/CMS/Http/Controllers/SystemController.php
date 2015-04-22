<?php namespace KodiCMS\CMS\Http\Controllers;

use Carbon\Carbon;
use KodiCMS\CMS\Helpers\Date;
use KodiCMS\CMS\Helpers\WYSIWYG;

class SystemController extends System\BackendController {

	public function settings()
	{
		$htmlEditors = WYSIWYG::htmlSelect(WYSIWYG::TYPE_HTML);
		$codeEditors = WYSIWYG::htmlSelect(WYSIWYG::TYPE_CODE);
		$dateFormats = Date::getFormats();

		// TODO: сделать вывод языков в нормальном формате
		$availableLocales = ['ru' => 'ru', 'en' => 'en'];

		$this->setContent('system.settings', compact('htmlEditors', 'codeEditors', 'dateFormats', 'availableLocales'));
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
