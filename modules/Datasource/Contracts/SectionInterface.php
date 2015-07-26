<?php namespace KodiCMS\Datasource\Contracts;

use KodiCMS\Datasource\Document;
use KodiCMS\Datasource\Model\Section;
use KodiCMS\Datasource\Model\SectionFolder;

interface SectionInterface
{
	/**
	 * @return integer
	 */
	public function getId();

	/**
	 * @return string
	 */
	public function getDocumentPrimaryKey();

	/**
	 * @return string
	 */
	public function getDocumentTitleKey();

	/**
	 * @return string
	 */
	public function getName();

	/**
	 * @return string
	 */
	public function getIcon();

	/**
	 * @return string
	 */
	public function getDescription();

	/**
	 * @return SectionType|null
	 */
	public function getType();

	/**
	 * @return SectionHeadlineInterface
	 */
	public function getHeadline();

	/**
	 * @return string
	 */
	public function getHeadlineClass();

	/**
	 * @return string
	 */
	public function getToolbar();

	/**
	 * @return array
	 */
	public function getSystemFields();

	/**
	 * @return array
	 */
	public function getFields();

	/**
	 * @return string
	 */
	public function getSectionTableName();

	/**
	 * @return Document
	 */
	public function getEmptyDocument();

	/**
	 * @param integer $id
	 * @return Document
	 */
	public function getDocumentById($id);

	/**
	 * @return string
	 */
	public function getDocumentClass();

	/**
	 * @return string
	 */
	public function getCreateDocumentTitle();

	/**
	 * @param string $name
	 * @return string
	 */
	public function getEditDocumentTitle($name = '');
}