<?php

namespace KodiCMS\SleepingOwlAdmin\Columns\Column;

class Checkbox extends BaseColumn
{
    public function __construct()
    {
        parent::__construct();
        $this->setLabel('<input type="checkbox" class="adminCheckboxAll"/>');
        $this->setOrderable(false);
    }

    /**
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function render()
    {
        return app('sleeping_owl.template')->view('column.checkbox', [
            'value' => $this->getModel()->getKey(),
        ]);
    }
}
