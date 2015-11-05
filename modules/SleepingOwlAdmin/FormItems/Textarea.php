<?php

namespace KodiCMS\SleepingOwlAdmin\FormItems;

class Textarea extends NamedFormItem
{
    /**
     * @var string
     */
    protected $view = 'textarea';

    /**
     * @var int
     */
    protected $rows = 10;

    /**
     * @param int|null $rows
     *
     * @return $this|int
     */
    public function rows($rows = null)
    {
        if (is_null($rows)) {
            return $this->rows;
        }
        $this->rows = $rows;

        return $this;
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return parent::getParams() + [
            'name'     => $this->name(),
            'label'    => $this->label(),
            'readonly' => $this->readonly(),
            'value'    => $this->value(),
            'rows'     => $this->rows(),
        ];
    }
}
