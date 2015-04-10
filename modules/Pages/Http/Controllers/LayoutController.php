<?php namespace KodiCMS\Pages\Http\Controllers;

use KodiCMS\CMS\Http\Controllers\AbstractFileController;
use KodiCMS\Pages\Model\LayoutCollection;

class LayoutController extends AbstractFileController
{
	/**
	 * @var string
	 */
	public $moduleNamespace = 'pages::';

	/**
	 * @var LayoutCollection
	 */
	protected $collection;

	protected function getCollection()
	{
		return new LayoutCollection();
	}

	protected function getSectionPrefix()
	{
		return 'layout';
	}
}