<?php

namespace KodiCMS\Datasource\Contracts;

use KodiCMS\Datasource\FieldType;
use Illuminate\Validation\Validator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Schema\Blueprint;
use KodiCMS\Widgets\Contracts\Widget as WidgetInterface;
use KodiCMS\CMS\Http\Controllers\System\TemplateController;

interface FieldInterface
{
    /**
     * @return int
     */
    public function getId();

    /**
     * @return string
     */
    public function getName();

    /**
     * @return int
     */
    public function getPosition();

    /**
     * @return FieldType
     */
    public function getType();


    /**
     * @return string
     */
    public function getTypeTitle();

    /**
     * @return string
     */
    public function getKey();

    /**
     * @return string
     */
    public function getDBKey();

    /**
     * @return mixed
     */
    public function getDefaultValue();

    /**
     * @return string
     */
    public function getDatabaseDefaultValue();

    /**
     * @return int
     */
    public function getRelatedSectionId();

    /**
     * @return int
     */
    public function getRelatedFieldId();

    /**
     * @return string
     */
    public function getRelatedTable();

    /**
     * @param DocumentInterface $document
     * @param Validator         $validator
     *
     * @return array
     */
    public function getValidationRules(DocumentInterface $document, Validator $validator);

    /**
     * @param SectionHeadlineInterface $headline
     *
     * @return array
     */
    public function getHeadlineParameters(SectionHeadlineInterface $headline);

    /**
     * @return string
     */
    public function getTablePrefix();

    /**
     * @return bool
     */
    public function isAttachedToSection();

    /**
     * @return SectionInterface
     */
    public function getSection();

    /**
     * @return bool
     */
    public function isSystem();

    /**
     * @return bool
     */
    public function isEditable();

    /**
     * @return bool
     */
    public function isRequired();

    /**
     * @return bool
     */
    public function isVisible();

    /**
     * @return bool
     */
    public function isSearchable();

    /**
     * @return bool
     */
    public function canBeUsedAsDocumentID();

    /**
     * @return bool
     */
    public function hasDatabaseColumn();

    /**
     * @return array
     */
    public function defaultSettings();

    /**************************************************************************
     * Events
     **************************************************************************/

    /**
     * @param Blueprint $table
     */
    public function onDatabaseCreate(Blueprint $table);

    /**
     * @param Blueprint $table
     */
    public function onDatabaseUpdate(Blueprint $table);

    /**
     * @param Blueprint $table
     */
    public function onDatabaseDrop(Blueprint $table);

    /**
     * @param DocumentInterface $document
     * @param mixed             $value
     */
    public function onDocumentFill(DocumentInterface $document, $value);

    /**
     * @param DocumentInterface $document
     * @param mixed             $value
     *
     * @return mixed
     */
    public function onSetDocumentAttribute(DocumentInterface $document, $value);

    /**
     * @param DocumentInterface $document
     * @param mixed             $value
     *
     * @return mixed
     */
    public function onGetDocumentValue(DocumentInterface $document, $value);

    /**
     * @param DocumentInterface $document
     * @param mixed             $value
     *
     * @return mixed
     */
    public function onGetFormValue(DocumentInterface $document, $value);

    /**
     * @param DocumentInterface $document
     * @param WidgetInterface   $widget
     * @param mixed             $value
     *
     * @return mixed
     */
    public function onGetWidgetValue(DocumentInterface $document, WidgetInterface $widget, $value);

    /**
     * @param DocumentInterface $document
     * @param mixed             $value
     *
     * @return mixed
     */
    public function onGetHeadlineValue(DocumentInterface $document, $value);

    /**
     * @param DocumentInterface $document
     * @param                   $value
     */
    public function onDocumentCreating(DocumentInterface $document, $value);

    /**
     * @param DocumentInterface $document
     * @param                   $value
     */
    public function onDocumentCreated(DocumentInterface $document, $value);

    /**
     * @param DocumentInterface $document
     * @param                   $value
     */
    public function onDocumentUpdating(DocumentInterface $document, $value);

    /**
     * @param DocumentInterface $document
     */
    public function onDocumentDeleting(DocumentInterface $document);

    /**
     * @param DocumentInterface  $document
     * @param TemplateController $controller
     */
    public function onControllerLoad(DocumentInterface $document, TemplateController $controller);

    /**************************************************************************
     * Database
     **************************************************************************/

    /**
     * @param Builder $query
     */
    public function querySelectColumn(Builder $query, DocumentInterface $document);

    /**
     * @param Builder $query
     * @param string  $dir
     */
    public function queryOrderBy(Builder $query, $dir = 'asc');

    /**
     * @return bool
     */
    public function isChangeableDatabaseField();

    /**
     * @param Blueprint $table
     */
    public function setDatabaseFieldType(Blueprint $table);

    /**************************************************************************
     * Render
     **************************************************************************/

    /**
     * @param DocumentInterface $document
     * @param string|null       $template
     *
     * @return string
     */
    public function renderDocumentTemplate(DocumentInterface $document, $template = null);

    /**************************************************************************
     * Filter
     **************************************************************************/
    /**
     * @return void
     */
    public function initFilterType();

    /**
     * @return FilterTypeInterface
     */
    public function getFilterType();

    /**
     * @return string
     */
    public function getFilterTypeClass();
}
