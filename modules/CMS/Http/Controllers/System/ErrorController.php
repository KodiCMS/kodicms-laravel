<?php namespace KodiCMS\CMS\Http\Controllers\System;

class ErrorController extends TemplateController
{
	public function show()
	{
		return $this->getRouterPath();
	}
}
