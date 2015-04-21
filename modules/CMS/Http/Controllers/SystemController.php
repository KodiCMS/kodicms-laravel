<?php namespace KodiCMS\CMS\Http\Controllers;

use KodiCMS\CMS\Helpers\WYSIWYG;

class SystemController extends System\BackendController {

	public function settings()
	{
		$htmlEditors = WYSIWYG::htmlSelect(WYSIWYG::TYPE_HTML);
		$codeEditors = WYSIWYG::htmlSelect(WYSIWYG::TYPE_CODE);
		$dateFormats = config('cms.date_format_list', []);

		$this->setContent('system.settings', compact('htmlEditors', 'codeEditors', 'dateFormats'));
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
