<?php

namespace KodiCMS\Support\Model\Fields;

class DateField extends TextField
{
    protected function boot()
    {
        parent::boot();

        $this->setAttributes([
            'class' => 'datepicker',
        ]);
    }
}
