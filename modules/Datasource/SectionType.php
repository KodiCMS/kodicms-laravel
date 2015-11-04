<?php

namespace KodiCMS\Datasource;

use KodiCMS\Datasource\Contracts\SectionTypeInterface;

class SectionType implements SectionTypeInterface
{
    /**
     * @param array $settings
     *
     * @return bool
     */
    public static function isValid(array $settings)
    {
        return isset($settings['class']);
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
    protected $document = null;

    /**
     * @var string
     */
    protected $create_template = null;

    /**
     * @var string
     */
    protected $edit_template = null;

    /**
     * @param string $type
     * @param array  $settings
     */
    public function __construct($type, array $settings)
    {
        foreach (array_only($settings, [
            'class',
            'title',
            'document',
            'icon',
            'create_template',
            'edit_template',
        ]) as $key => $value) {
            $this->{$key} = $value;
        }

        $this->type = $type;
    }

    /**
     * @return bool
     */
    public function isExists()
    {
        return class_exists($this->class);
    }

    /**
     * @return bool
     */
    public function isDocumentClassExists()
    {
        return class_exists($this->document);
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
    public function getDocumentClassName()
    {
        if ($this->isDocumentClassExists()) {
            return $this->document;
        }

        return \KodiCMS\Datasource\Document::class;
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
    public function getIcon()
    {
        return $this->icon;
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
    public function getCreateTemplate()
    {
        return is_null($this->create_template) ? 'datasource::section.create' : $this->create_template;
    }

    /**
     * @return string
     */
    public function getEditTemplate()
    {
        return is_null($this->edit_template) ? 'datasource::section.edit' : $this->edit_template;
    }

    /**
     * @return string
     */
    public function getLink()
    {
        return route('backend.datasource.create', [$this->getType()]);
    }
}
