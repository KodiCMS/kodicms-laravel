<?php

namespace KodiCMS\SleepingOwlAdmin\Columns\Column;

class Url extends NamedColumn
{
    /**
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function render()
    {
        return app('sleeping_owl.template')->view('column.url', [
            'url' => $this->getModelValue(),
        ]);
    }
}
