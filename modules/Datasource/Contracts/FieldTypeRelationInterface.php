<?php namespace KodiCMS\Datasource\Contracts;

interface FieldTypeRelationInterface
{
	/**
	 * @return integer
	 */
	public function getRelatedSectionId();
}