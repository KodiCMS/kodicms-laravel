<?php namespace KodiCMS\Pages\Model;

use KodiCMS\CMS\Model\FileCollection;

class LayoutCollection extends FileCollection
{
	/**
	 * @var string
	 */
	protected $fileClass = '\\KodiCMS\Pages\Model\Layout';

	public function __construct()
	{
		return parent::__construct(layouts_path());
	}
}