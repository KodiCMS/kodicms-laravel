<?php

namespace KodiCMS\SleepingOwlAdmin\Columns\Column;

class Checkbox extends BaseColumn
{
    /**
     *
     */
    public function __construct()
    {
        parent::__construct();

        $this->label('<input type="checkbox" class="adminCheckboxAll"/>');
        $this->orderable(false);
    }

    /**
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function render()
    {
        return app('sleeping_owl.template')->view('column.checkbox', [
            'value' => $this->instance->getKey(),
        ]);
    }
}
