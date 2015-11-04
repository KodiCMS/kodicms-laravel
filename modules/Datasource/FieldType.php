<?php

namespace KodiCMS\Datasource;

use KodiCMS\Datasource\Fields\Field;
use KodiCMS\Datasource\Contracts\FieldTypeInterface;

class FieldType implements FieldTypeInterface
{
    const PRIMITIVE = 'Primitive';
    const FILE = 'File';
    const RELATION = 'Relation';

    /**
     * @param array $settings
     *
     * @return bool
     */
    public static function isValid(array $settings)
    {
        if (! isset($settings['class'])) {
            return false;
        }

        return true;
    }

    /**
     * @var string
     */
    protected $class;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $icon = 'table';

    /**
     * @var string
     */
    protected $category;

    /**
     * @var string
     */
    protected $edit_template = null;

    /**
     * @var string
     */
    protected $document_template = null;

    /**
     * @var string
     */
    protected $widget_template = null;

    /**
     * @param string $type
     * @param array  $settings
     */
    public function __construct($type, array $settings)
    {
        foreach (array_only($settings, [
            'class',
            'type',
            'title',
            'icon',
            'category',
            'edit_template',
            'document_template',
            'widget_template',
        ]) as $key => $value) {
            $this->{$key} = $value;
        }

        $this->type = $type;
    }

    /**
     * @return bool
     */
    public function getCategory()
    {
        return is_null($this->category) ? static::PRIMITIVE : $this->category;
    }

    /**
     * @return Field
     */
    public function getFieldObject()
    {
        return new $this->class;
    }

    /**
     * @return bool
     */
    public function isExists()
    {
        return class_exists($this->class);
    }

    /**
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * @return string|null
     */
    public function getEditTemplate()
    {
        return $this->edit_template;
    }

    /**
     * @return string
     */
    public function getDocumentTemplate()
    {
        if (is_null($template = $this->document_template)) {
            $template = 'datasource::document.field.'.$this->getType();
        }

        return $template;
    }

    /**
     * @return string
     */
    public function getWidgetTemplate()
    {
        if (is_null($template = $this->widget_template)) {
            $template = 'datasource::widgets.partials.field';
        }

        return $template;
    }
}
