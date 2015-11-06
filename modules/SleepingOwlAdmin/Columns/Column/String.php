<?php

namespace KodiCMS\SleepingOwlAdmin\Columns\Column;

class String extends NamedColumn
{
    /**
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function render()
    {
        return app('sleeping_owl.template')->view('column.string', [
            'value'  => $this->getModelValue(),
            'append' => $this->getAppend(),
        ]);
    }
}
