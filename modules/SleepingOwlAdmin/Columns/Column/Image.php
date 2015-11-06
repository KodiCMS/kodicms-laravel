<?php

namespace KodiCMS\SleepingOwlAdmin\Columns\Column;

class Image extends NamedColumn
{
    /**
     * @param $name
     */
    public function __construct($name)
    {
        parent::__construct($name);
        $this->setOrderable(false);
    }

    /**
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function render()
    {
        $value = $this->getModelValue();
        if (! empty($value) && (strpos($value, '://') === false)) {
            $value = asset($value);
        }

        return app('sleeping_owl.template')->view('column.image', [
            'value'  => $value,
            'append' => $this->getAppend(),
        ]);
    }
}
