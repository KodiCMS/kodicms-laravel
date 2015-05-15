<?php namespace KodiCMS\Widgets\Http\Controllers\API;

use KodiCMS\CMS\Http\Controllers\API\AbstractFileController;
use KodiCMS\Widgets\Model\SnippetCollection;

class SnippetController extends AbstractFileController
{
	/**
	 * @var bool
	 */
	public $authRequired = TRUE;

	/**
	 * @var string
	 */
	public $moduleNamespace = 'widgets::';

	/**
	 * @return SnippetCollection
	 */
	protected function getCollection()
	{
		return new SnippetCollection();
	}

	/**
	 * @return string
	 */
	protected function getSectionPrefix()
	{
		return 'snippet';
	}

	/**
	 * @param string $filename
	 * @return string
	 */
	protected function getRedirectToEditUrl($filename)
	{
		return route('backend.snippet.edit', [$filename]);
	}

	public function getList()
	{
		$this->setContent($snippets = (new SnippetCollection)->getHTMLSelectChoices());
	}
}