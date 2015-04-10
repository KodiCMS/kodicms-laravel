<?php namespace KodiCMS\Widgets\Model;

use KodiCMS\CMS\Model\FileCollection;

class SnippetCollection extends FileCollection
{
	public function __construct()
	{
		return parent::__construct(snippets_path());
	}
}