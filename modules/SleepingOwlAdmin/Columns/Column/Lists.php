<?php

namespace KodiCMS\SleepingOwlAdmin\Columns\Column;

class Lists extends NamedColumn
{
    /**
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function render()
    {
        return app('sleeping_owl.template')->view('column.lists', [
            'values' => $this->getValue($this->instance, $this->name()),
            'append' => $this->append(),
        ]);
    }
}
