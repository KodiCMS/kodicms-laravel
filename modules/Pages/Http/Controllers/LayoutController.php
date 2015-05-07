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