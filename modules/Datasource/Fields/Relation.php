<?php namespace KodiCMS\Datasource\Fields;

use DatasourceManager;
use KodiCMS\Datasource\Model\Field;
use KodiCMS\Datasource\Contracts\DocumentInterface;

class Relation extends Field
{
	/**
	 * The relations to eager load on every query.
	 *
	 * @var array
	 */
	protected $with = ['relatedSection'];

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
	protected function fetchBackendTemplateValues(DocumentInterface $document)
	{
		$relatedSection = $this->relatedSection;
		return array_merge(parent::fetchBackendTemplateValues($document), [
			'relatedDocument' => $this->getDocumentRelation($document, $relatedSection)->first(),
			'relatedSection' => $relatedSection,
			'relatedField' => $this->relatedField
		]);
	}
}