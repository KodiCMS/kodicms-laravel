<?php namespace KodiCMS\CMS\Http\Controllers\System;

class BackendController extends TemplateController
{
	/**
	 * @var bool
	 */
	public $authRequired = TRUE;

	public function before()
	{
		parent::before();
	}

	public function after()
	{
		$this->template->with('bodyId', $this->getRouterPath());

		parent::after();
	}
}
