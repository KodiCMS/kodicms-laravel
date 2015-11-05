<?php

namespace KodiCMS\SleepingOwlAdmin\Columns\Column;

class Count extends NamedColumn
{
    /**
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function render()
    {
        return app('sleeping_owl.template')->view('column.count', [
            'value'  => count($this->getValue($this->instance, $this->name())),
            'append' => $this->append(),
        ]);
    }
}
