<?php namespace KodiCMS\CMS\Http\Controllers;

use Carbon\Carbon;
use KodiCMS\CMS\Helpers\WYSIWYG;

class SystemController extends System\BackendController {

	public function settings()
	{
		$htmlEditors = WYSIWYG::htmlSelect(WYSIWYG::TYPE_HTML);
		$codeEditors = WYSIWYG::htmlSelect(WYSIWYG::TYPE_CODE);
		$dateFormats = config('cms.date_format_list', []);
		$dateFormats = array_combine($dateFormats, $dateFormats);

		foreach($dateFormats as $format => $value)
		{
			$dateFormats[$format] = Carbon::now()->format($format);
		}

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
