<?php namespace KodiCMS\CMS\Http\Controllers;

class WelcomeController extends TemplateController {

	protected $template = 'CMS::app';

	public function index()
	{
		return "Hello world";
	}
}
