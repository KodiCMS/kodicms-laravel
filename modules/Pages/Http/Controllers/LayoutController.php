<?php namespace KodiCMS\Pages\Http\Controllers;

use KodiCMS\Pages\Model\LayoutCollection;
use KodiCMS\CMS\Http\Controllers\AbstractFileController;

class LayoutController extends AbstractFileController
{
	/**
	 * @var string
	 */
	public $moduleNamespace = 'pages::';

	/**
	 * @var array
	 */
	protected $editors = NULL;

	/**
	 * @var LayoutCollection
	 */
	protected $collection;

	/**
	 * @return LayoutCollection
	 */
	protected function getCollection()
	{
		return new LayoutCollection();
	}

	/**
	 * @return string
	 */
	protected function getSectionPrefix()
	{
		return 'layout';
	}
}