<?php namespace KodiCMS\Datasource\Fields;

use DatasourceManager;
use KodiCMS\Datasource\Model\Field;
use KodiCMS\Datasource\Contracts\DocumentInterface;
use KodiCMS\Datasource\Contracts\FieldTypeRelationInterface;

abstract class Relation extends Field implements FieldTypeRelationInterface
{
	/**
	 * The relations to eager load on every query.
	 *
	 * @var array
	 */
	protected $with = ['relatedSection'];

	/**
	 * @var bool
	 */
	protected $isOrderable = false;

	/**
	 * @return array
	 */
	public function getSectionList()
	{
		return DatasourceManager::getSectionsFormHTML();
	}

	/**
	 * @return integer
	 */
	public function getRelatedSectionId()
	{
		return $this->related_section_id;
	}

	/**
	 * @return string
	 */
	public function getRelatedDBKey()
	{
		return $this->getDBKey() . '_related';
	}

	/**
	 * @return string
	 */
	public function getRelationName()
	{
		return camel_case($this->getDBKey() . '_relation');
	}

	/**
	 * @param DocumentInterface $document
	 * @return array
	 */
	protected function fetchDocumentTemplateValues(DocumentInterface $document)
	{
		$relatedSection = $this->relatedSection;
		return array_merge(parent::fetchDocumentTemplateValues($document), [
			'relatedDocument' => $this->getDocumentRelation($document, $relatedSection)->first(),
			'relatedSection' => $relatedSection,
			'relatedField' => $this->relatedField
		]);
	}

	/**
	 * @param DocumentInterface $document
	 * @param mixed $value
	 *
	 * @return mixed
	 */
	public function onGetWidgetValue(DocumentInterface $document, $value)
	{
		return !is_null($related = $document->getAttribute($this->getRelationName()))
			? $related->toArray()
			: $value;
	}

	/**
	 * @param DocumentInterface $document
	 */
	public function onRelatedDocumentDeleting(DocumentInterface $document)
	{

	}
}