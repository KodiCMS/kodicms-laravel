<?php

namespace KodiCMS\Datasource;

use Schema;
use KodiCMS\Datasource\Contracts\FieldInterface;
use KodiCMS\Datasource\Contracts\SectionInterface;
use KodiCMS\Datasource\Contracts\FieldTypeOnlySystemInterface;

class FieldManager extends AbstractManager
{
    /**
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;

        foreach ($this->config as $type => $data) {
            if (! FieldType::isValid($data)) {
                continue;
            }
            $this->types[$type] = new FieldType($type, $data);
        }
    }

    /**
     * @return array
     */
    public function getAvailableTypesForSelect()
    {
        $types = [];

        foreach ($this->getAvailableTypes() as $key => $typeObject) {
            $interfaces = class_implements($typeObject->getClass());

            if (in_array(FieldTypeOnlySystemInterface::class, $interfaces)) {
                continue;
            }

            $types[$typeObject->getCategory()][$key] = $typeObject->getTitle();
        }

        return $types;
    }

    /**
     * @return array
     */
    public function getEmptyObjects()
    {
        $objects = [];
        foreach ($this->getAvailableTypes() as $key => $typeObject) {
            $objects[$key] = $typeObject->getFieldObject();
        }

        return $objects;
    }

    /**
     * @param SectionInterface $section
     * @param FieldInterface   $field
     *
     * @return bool
     */
    public function attachFieldToSection(SectionInterface $section, FieldInterface $field)
    {
        $this->addFieldToSectionTable($section, $field);
        $field->update([
            'section_id' => $section->getId(),
        ]);

        return true;
    }

    /**
     * @param SectionInterface $section
     * @param FieldInterface   $field
     *
     * @return bool
     */
    public function addFieldToSectionTable(SectionInterface $section, FieldInterface $field)
    {
        if (! $field->hasDatabaseColumn()) {
            return true;
        }

        Schema::table($section->getSectionTableName(), function ($table) use ($field) {
            $field->onDatabaseCreate($table);
            $field->setDatabaseFieldType($table);
        });

        return true;
    }

    /**
     * @param FieldInterface $field
     *
     * @return bool
     */
    public function updateSectionTableField(FieldInterface $field)
    {
        if (! $field->hasDatabaseColumn() or ! $field->isChangeableDatabaseField()) {
            return true;
        }

        $section = $field->getSection();

        if (! Schema::hasColumn($section->getSectionTableName(), $field->getDBKey())) {
            return false;
        }

        Schema::table($section->getSectionTableName(), function ($table) use ($field) {
            $field->onDatabaseUpdate($table);
            $field->setDatabaseFieldType($table)->change();
        });

        return true;
    }

    /**
     * @param FieldInterface $field
     *
     * @return bool
     */
    public function dropSectionTableField(FieldInterface $field)
    {
        if (! $field->hasDatabaseColumn()) {
            return true;
        }

        $section = $field->getSection();
        if (! Schema::hasColumn($section->getSectionTableName(), $field->getDBKey())) {
            return false;
        }

        Schema::table($section->getSectionTableName(), function ($table) use ($field) {
            $field->onDatabaseDrop($table);
        });

        return true;
    }
}
