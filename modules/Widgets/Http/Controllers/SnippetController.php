<?php namespace KodiCMS\Widgets\Http\Controllers;

use KodiCMS\CMS\Http\Controllers\AbstractFileController;
use KodiCMS\Widgets\Model\SnippetCollection;

class SnippetController extends AbstractFileController
{
	/**
	 * @var string
	 */
	public $moduleNamespace = 'widgets::';

	/**
	 * @var LayoutCollection
	 */
	protected $collection;

	/**
	 * @var array
	 */
	protected $editors = ['ace', 'ckeditor'];

	protected function getCollection()
	{
		return new SnippetCollection();
	}

	protected function getSectionPrefix()
	{
		return 'snippet';
	}
}