<?php

namespace KodiCMS\Datasource;

use Schema;
use Illuminate\Support\Collection;
use KodiCMS\Datasource\Fields\Field;
use KodiCMS\Datasource\Model\Section;
use KodiCMS\Datasource\Contracts\FieldInterface;
use KodiCMS\Widgets\Manager\WidgetManagerDatabase;
use KodiCMS\Datasource\Contracts\SectionInterface;
use KodiCMS\Datasource\Contracts\FieldGroupInterface;

class DatasourceManager extends AbstractManager
{
    /**
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;

        foreach ($this->config as $type => $data) {
            if (! SectionType::isValid($data)) {
                continue;
            }

            $this->types[$type] = new SectionType($type, $data);
        }
    }

    /**
     * @param array        $types
     * @param int|null $sectionId
     *
     * @return Collection
     */
    public function getWidgetsBySection(array $types, $sectionId = null)
    {
        $widgets = WidgetManagerDatabase::getWidgetsByType($types);

        if (is_null($sectionId)) {
            return $widgets;
        }

        return $widgets->filter(function ($widget) use ($sectionId) {
            return $widget->section_id == (int) $sectionId;
        });
    }

    /**
     * @param array|null $types
     *
     * @return array
     */
    public function getSections(array $types = null)
    {
        $query = Section::query();

        if (! empty($types)) {
            $query->whereIn('type', $types);
        }

        $sections = [];

        foreach ($query->get() as $section) {
            if (! $this->typeExists($section->type)) {
                continue;
            }

            $sections[$section->id] = $section;
        }

        return $sections;
    }

    /**
     * @param array|null $types
     *
     * @return array
     */
    public function getSectionsFormHTML(array $types = null)
    {
        $select = [trans('cms::core.label.not_set')];
        foreach ($this->getSections($types) as $section) {
            $select[$section->getType()->getTitle()][$section->getId()] = $section->getName();
        }

        return $select;
    }

    /**
     * @param SectionInterface $section
     */
    public function createTableSection(SectionInterface $section)
    {
        $this->dropSectionTable($section);

        Schema::create($section->getSectionTableName(), function ($table) use ($section) {
            foreach ($section->getSystemFields() as $field) {
                $this->appendDatabaseField($table, $section, $field);
            }
        });
    }

    /**
     * @param                                    $table
     * @param SectionInterface                   $section
     * @param FieldGroupInterface|FieldInterface $field
     * @param bool|true                          $system
     */
    protected function appendDatabaseField($table, SectionInterface $section, $field, $system = true)
    {
        if ($field instanceof FieldInterface) {
            $field->is_system = $system;
            if ($field = $section->fields()->save($field)) {
                $field->setDatabaseFieldType($table);
            }
        } elseif ($field instanceof FieldGroupInterface) {
            $group = $field;

            $group->section_id = $section->getId();
            $group->save();

            foreach ($group->getFields() as $field) {
                $field->group_id = $group->id;
                $this->appendDatabaseField($table, $section, $field, $system);
            }
        }
    }

    /**
     * @param SectionInterface $section
     */
    public function dropSectionTable(SectionInterface $section)
    {
        Schema::dropIfExists($section->getSectionTableName());
    }

    /**
     * @param SectionInterface $section
     * @param FieldInterface   $field
     */
    public function addNewField(SectionInterface $section, FieldInterface $field)
    {
        if ($field = $section->fields()->save($field)) {
            FieldManager::addFieldToSectionTable($section, $field);
        }
    }

    /**
     * @param SectionInterface             $section
     * @param FieldInterface|Field|int $fieldId
     */
    public function attachField(SectionInterface $section, $fieldId)
    {
        if ($fieldId instanceof FieldInterface) {
            $field = $fieldId;
        } elseif (is_int($fieldId)) {
            $field = Field::find($fieldId);
        }

        FieldManager::attachFieldToSection($section, $field);
    }
}
