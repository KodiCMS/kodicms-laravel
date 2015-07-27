<?php namespace KodiCMS\Datasource\Fields\Relation;

use KodiCMS\Datasource\Fields\Relation;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Eloquent\Builder;
use KodiCMS\Datasource\Contracts\DocumentInterface;
use KodiCMS\Datasource\Contracts\FieldTypeRelationInterface;
use Illuminate\Database\Eloquent\Relations\HasOne as HasOneRelation;

class HasOne extends Relation implements FieldTypeRelationInterface
{
	/**
	 * @param Blueprint $table
	 * @return \Illuminate\Support\Fluent
	 */
	public function setDatabaseFieldType(Blueprint $table)
	{
		return $table->integer($this->getDBKey())->nullable();
	}

	/**
	 * @return string
	 */
	public function getRelatedDBKey()
	{
		return $this->getDBKey() . '_relation';
	}
	/**
	 * @param Builder $query
	 * @param DocumentInterface $document
	 */
	public function querySelectColumn(Builder $query, DocumentInterface $document)
	{
		$query
			->addSelect($this->getDBKey())
			->with([$this->getRelatedDBKey()]);
	}

	/**
	 * @param DocumentInterface $document
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\Relation
	 */
	public function getDocumentRalation(DocumentInterface $document)
	{
		$section = $this->relatedSection()->first();
		$instance = $section->getEmptyDocument();
		return new HasOneRelation($instance->newQuery(), $document, $section->getSectionTableName() . '.' . $section->getDocumentPrimaryKey(), $this->getDBKey(), null);
	}

	/**
	 * @param DocumentInterface $document
	 * @param mixed $value
	 *
	 * @return mixed
	 */
	public function onGetWidgetValue(DocumentInterface $document, $value)
	{
		return $document->relationLoaded($this->getRelatedDBKey())
			? $document->getRelation($this->getRelatedDBKey())->toArray()
			: $value;
	}

	/**
	 * @param integer $value
	 *
	 * @return array|static[]
	 */
	public function getRelatedDocumentValue($value)
	{
		$section = $this->relatedSection()->first();

		return \DB::table($section->getSectionTableName())
			->addSelect($section->getDocumentPrimaryKey())
			->addSelect($section->getDocumentTitleKey())
			->where($section->getDocumentPrimaryKey(), $value)
			->lists($section->getDocumentTitleKey(), $section->getDocumentPrimaryKey());
	}

	/**
	 * @return array
	 */
	public function toArray()
	{
		return array_merge(parent::toArray(), ['relatedSection' => $this->relatedSection()->first()]);
	}
}