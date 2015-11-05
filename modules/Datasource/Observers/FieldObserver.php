<?php

namespace KodiCMS\Datasource\Observers;

use FieldManager;
use KodiCMS\Datasource\Model\Field;

class FieldObserver
{
    /**
     * @param Field $field
     */
    public function creating(Field $field)
    {
        $field->position = $field->getLastPosition() + 1;

        if (method_exists($field, 'onCreating')) {
            app()->call([$field, 'onCreating']);
        }
    }

    /**
     * @param Field $field
     */
    public function created(Field $field)
    {
        if (method_exists($field, 'onCreated')) {
            app()->call([$field, 'onCreated']);
        }
    }

    /**
     * @param Field $field
     */
    public function updating(Field $field)
    {
        if (method_exists($field, 'onUpdating')) {
            app()->call([$field, 'onUpdating']);
        }
    }

    /**
     * @param Field $field
     */
    public function updated(Field $field)
    {
        if ($field->isAttachedToSection()) {
            FieldManager::updateSectionTableField($field);
        }

        if (method_exists($field, 'onUpdated')) {
            app()->call([$field, 'onUpdated']);
        }
    }

    /**
     * @param Field $field
     */
    public function deleting(Field $field)
    {
        if (method_exists($field, 'onDeleting')) {
            app()->call([$field, 'onDeleting']);
        }
    }

    /**
     * @param Field $field
     */
    public function deleted(Field $field)
    {
        if (method_exists($field, 'onDeleted')) {
            app()->call([$field, 'onDeleted']);
        }

        if ($field->isAttachedToSection()) {
            FieldManager::dropSectionTableField($field);
        }
    }
}
