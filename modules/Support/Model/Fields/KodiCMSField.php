<?php

namespace KodiCMS\Support\Model\Fields;

use KodiCMS\Support\Model\ModelField;

abstract class KodiCMSField extends ModelField
{
    /**
     * @var string
     */
    protected $template = 'cms::model_fields.default';

    protected function boot()
    {
        $this->setAttributes([
            'class' => ['form-control'],
        ]);

        $this->getLabel()->setAttributes([
                'class' => ['control-label'],
            ]);

        $this->getGroup()->setAttributes([
                'class' => ['form-group'],
            ])->setSettings([
                'labelCol' => 'col-md-3',
                'fieldCol' => 'col-md-9',
            ])->setTemplate($this->template);
    }

    /**
     * @param array $attributes
     *
     * @return string
     */
    public function renderFormField(array $attributes = [])
    {
        $attributes['tabindex'] = $this->getTabIndex();

        return parent::renderFormField($attributes);
    }
}
