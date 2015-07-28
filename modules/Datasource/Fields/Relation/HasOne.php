<?php namespace KodiCMS\Datasource\Fields\Relation;

use KodiCMS\Datasource\Fields\Relation;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Schema\Blueprint;
use KodiCMS\Datasource\Contracts\SectionInterface;
use KodiCMS\Datasource\Repository\FieldRepository;
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
	 * @param Builder $query
	 * @param DocumentInterface $document
	 */
	public function querySelectColumn(Builder $query, DocumentInterface $document)
	{
		$query->addSelect($this->getDBKey())->with($this->getRelationName());
	}

	/**
	 * @param DocumentInterface $document
	 * @param SectionInterface $relatedSection
	 * @return BelongsToRelation
	 */
	public function getDocumentRalation(DocumentInterface $document, SectionInterface $relatedSection)
	{
		$instance = $relatedSection->getEmptyDocument()->newQuery();

		$foreignKey = $relatedSection->getSectionTableName() . '.' . $relatedSection->getDocumentPrimaryKey();
		$otherKey = $this->getDBKey();
		$relation = $this->getRelationName();

		return new HasOneRelation($instance, $document, $foreignKey, $otherKey, $relation);
	}

	/**
	 * @param DocumentInterface $document
	 * @param mixed $value
	 *
	 * @return mixed
	 */
	public function onGetWidgetValue(DocumentInterface $document, $value)
	{
		return $document->relationLoaded($this->getRelationName())
			? $document->getRelation($this->getRelationName())->toArray()
			: $value;
	}

	/**
	 * @param DocumentInterface $document
	 * @param mixed $value
	 *
	 * @return mixed
	 */
	public function onGetHeadlineValue(DocumentInterface $document, $value)
	{
		return !is_null($relatedDocument = $document->getAttribute($this->getRelationName()))
			? \HTML::link($relatedDocument->getEditLink(), $relatedDocument->getTitle(), ['class' => 'popup'])
			: null;
	}

	/**
	 * @param DocumentInterface $document
	 *
	 * @return array|static[]
	 */
	public function getRelatedDocumentValue(DocumentInterface $document)
	{
		$section = $this->relatedSection()->first();

		return \DB::table($section->getSectionTableName())
			->addSelect($section->getDocumentPrimaryKey())
			->addSelect($section->getDocumentTitleKey())
			->where($section->getDocumentPrimaryKey(), $document->getAttribute($this->getDBKey()))
			->lists($section->getDocumentTitleKey(), $section->getDocumentPrimaryKey());
	}

	/**
	 * @return array
	 */
	public function toArray()
	{
		return array_merge(parent::toArray(), ['relatedSection' => $this->relatedSection()->first()]);
	}

	public function onCreated(FieldRepository $repository)
	{
		$repository->create([
			'type' => 'belongs_to',
			'ds_id' => $this->getRelatedSectionId(),
			'is_system' => 1,
			'key' => $this->getDBKey() . '_belongs_to',
			'name' => $this->getSection()->getName(),
			'related_ds' => $this->getSection()->getId()
		]);
	}

	public function onDeleted(FieldRepository $repository)
	{
		$repository->query()
			->where('key', $this->getDBKey() . '_belongs_to')
			->where('ds_id', $this->getRelatedSectionId())
			->where('related_ds', $this->getSection()->getId())
			->delete();
	}
}