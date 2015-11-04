<?php

namespace KodiCMS\Datasource\Traits;

trait WidgetDatasourceFields
{
    /**
     * @return array
     */
    public function getSelectedFields()
    {
        return (array) $this->selected_fields;
    }

    /**
     * @param array $fields
     */
    public function setSelectedFields($fields)
    {
        $this->settings['selected_fields'] = (array) $fields;
    }
}
