<?php namespace KodiCMS\Datasource\Sections\DefaultSection;

class Section extends \KodiCMS\Datasource\Model\Section {

	/**
	 * @return string
	 */
	public function getDocumentClass()
	{
		return Document::class;
	}
}