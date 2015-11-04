<?php

namespace KodiCMS\Datasource\Fields\Primitive;

use WYSIWYG;
use KodiCMS\Datasource\Fields\Primitive;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Schema\Blueprint;
use KodiCMS\Datasource\Contracts\DocumentInterface;
use KodiCMS\Widgets\Contracts\Widget as WidgetInterface;

class HTML extends Primitive
{
    /**
     * @var bool
     */
    protected $changeableDatabaseField = false;

    /**
     * @var bool
     */
    protected $isOrderable = false;

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
            'filter_html'       => false,
            'allowed_tags'      => '<b><i><p><ul><li><ol>',
            'wysiwyg'           => WYSIWYG::getDefaultHTMLEditorId(),
        ];
    }

    /**
     * @return bool
     */
    public function isRemoveEmptyTags()
    {
        return $this->getSetting('remove_empty_tags');
    }

    /**
     * @return bool
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
     * @return string
     */
    public function getHeadlineType()
    {
        return 'html';
    }

    /**
     * @param DocumentInterface $document
     * @param                   $value
     *
     * TODO: реализовать фильтрацию тегов
     */
    public function onDocumentUpdating(DocumentInterface $document, $value)
    {
        if ($this->isFilterHTML()) {
            $value = $value;
        }

        $document->setAttribute($this->getDBKey(), $value);
        $document->setAttribute($this->getDBFilteredColumnKey(), WYSIWYG::applyFilter($this->getWysiwyg(), $value));
    }

    public function getDBFilteredColumnKey()
    {
        return $this->getDBKey().'_filtered';
    }

    /**
     * @param DocumentInterface $document
     * @param mixed             $value
     *
     * @return mixed
     */
    public function onGetHeadlineValue(DocumentInterface $document, $value)
    {
        return str_limit(strip_tags($value), 50);
    }

    /**
     * @param DocumentInterface $document
     * @param WidgetInterface   $widget
     * @param mixed             $value
     *
     * @return mixed
     */
    public function onGetWidgetValue(DocumentInterface $document, WidgetInterface $widget, $value)
    {
        return [
            'html'     => $value,
            'filtered' => $document->{$this->getDBFilteredColumnKey()},
        ];
    }

    /**
     * @param Blueprint $table
     *
     * @return \Illuminate\Support\Fluent
     */
    public function setDatabaseFieldType(Blueprint $table)
    {
        $table->text($this->getDBFilteredColumnKey());

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

    /**
     * @param Builder           $query
     * @param DocumentInterface $document
     */
    public function querySelectColumn(Builder $query, DocumentInterface $document)
    {
        parent::querySelectColumn($query, $document);
        $query->addSelect($this->getDBFilteredColumnKey());
    }
}
