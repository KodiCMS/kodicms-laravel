<?php namespace KodiCMS\Datasource\Fields\Primitive;

use WYSIWYG;
use KodiCMS\Datasource\Fields\Primitive;
use Illuminate\Database\Schema\Blueprint;
use KodiCMS\Datasource\Contracts\DocumentInterface;

class HTML extends Primitive
{
	/**
	 * @var bool
	 */
	protected $changeableDatabaseField = false;

	/**
	 * @return array
	 */
	public function booleanSettings()
	{
		return ['remove_empty_tags', 'filter_html'];
	}

	/**
	 * @return array
	 */
	public function defaultSettings()
	{
		return [
			'remove_empty_tags' => false,
			'filter_html' => false,
			'allowed_tags' => '<b><i><p><ul><li><ol>',
			'wysiwyg' => WYSIWYG::getDefaultHTMLEditorId()
		];
	}

	/**
	 * @return boolean
	 */
	public function isRemoveEmptyTags()
	{
		return $this->getSetting('remove_empty_tags');
	}

	/**
	 * @return boolean
	 */
	public function isFilterHTML()
	{
		return $this->getSetting('filter_html');
	}

	/**
	 * @return array
	 */
	public function getAllowedHTMLTags()
	{
		return $this->getSetting('allowed_tags');
	}

	/**
	 * @return string
	 */
	public function getWysiwyg()
	{
		return $this->getSetting('wysiwyg');
	}

	/**
	 * @param DocumentInterface $document
	 * @param $value
	 *
	 * TODO: реализовать фильтрацию тегов
	 */
	public function onDocumentUpdating(DocumentInterface $document, $value)
	{
		if ($this->isFilterHTML())
		{
			$value = $value;
		}

		$document->setAttribute($this->getDBKey(), $value);
		$document->setAttribute($this->getDBFilteredColumnKey(), WYSIWYG::applyFilter($this->getWysiwyg(), $value));
	}

	public function getDBFilteredColumnKey()
	{
		return $this->getDBKey() . '_filtered';
	}

	/**
	 * @param Blueprint $table
	 * @return \Illuminate\Support\Fluent
	 */
	public function setDatabaseFieldType(Blueprint $table)
	{
		$table->text($this->getDBKey() . '_filtered');

		return $table->text($this->getDBKey());
	}

	/**
	 * @param Blueprint $table
	 */
	public function onDatabaseDrop(Blueprint $table)
	{
		parent::onDatabaseDrop($table);
		$table->dropColumn($this->getDBFilteredColumnKey());
	}
}