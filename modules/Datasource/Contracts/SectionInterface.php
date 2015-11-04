<?php

namespace KodiCMS\Datasource\Contracts;

use KodiCMS\Datasource\Document;

interface SectionInterface
{
    /**
     * @return int
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
    public function getLink();

    /**
     * @return int
     */
    public function getMenuPosition();

    /**
     * @return bool
     */
    public function showInRootMenu();

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
     * @return array
     */
    public function getHeadlineOrdering();

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
    public function getRelatedFields();

    /**
     * @return FieldsCollectionInterface
     */
    public function getFields();

    /**
     * @return string
     */
    public function getSectionTableName();

    /**
     * @return Document
     */
    public function getEmptyDocument(array $attributes = []);

    /**
     * @param array $attributes
     *
     * @return Document
     */
    public function newDocumentQuery(array $attributes = []);

    /**
     * @param int   $id
     * @param array $attributes
     *
     * @return Document
     */
    public function getDocumentById($id, array $attributes = []);

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
     *
     * @return string
     */
    public function getEditDocumentTitle($name = '');
}
