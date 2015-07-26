<?php namespace KodiCMS\Datasource\Fields\Primitive;

use KodiCMS\Datasource\Fields\Primitive;
use Illuminate\Database\Schema\Blueprint;
use KodiCMS\Datasource\Contracts\DocumentInterface;

class Textarea extends Primitive
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
		return ['allow_html', 'filter_html'];
	}

	/**
	 * @return array
	 */
	public function defaultSettings()
	{
		return [
			'allow_html' => false,
			'filter_html' => false,
			'allowed_tags' => '<b><i><p><ul><li><ol>',
			'rows' => 3
		];
	}

	/**
	 * @return integer
	 */
	public function getRows()
	{
		return $this->getSetting('rows');
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
	 * @return integer
	 */
	public function isAllowHTML()
	{
		return $this->getSetting('allow_html');
	}

	/**
	 * @param integer $rows
	 */
	public function setSettingRows($rows)
	{
		intval($rows);
		if ($rows < 0)
		{
			$rows = 1;
		}

		$this->fieldSettings['rows'] = $rows;
	}

	/**
	 * @param DocumentInterface $document
	 * @param $value
	 *
	 * TODO: реализовать фильтрацию тегов
	 */
	public function onDocumentUpdating(DocumentInterface $document, $value)
	{
		if (!$this->isAllowHTML())
		{
			$value = strip_tags($value);
		}
		elseif ($this->isFilterHTML())
		{
			$value = $value;
		}

		$document->setAttribute($this->getDBKey(), $value);
	}

	/**
	 * @param Blueprint $table
	 * @return \Illuminate\Support\Fluent
	 */
	public function setDatabaseFieldType(Blueprint $table)
	{
		return $table->text($this->getDBKey());
	}
}