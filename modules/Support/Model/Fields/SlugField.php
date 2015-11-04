<?php

namespace KodiCMS\Support\Model\Fields;

class SlugField extends TextField
{
    protected function boot()
    {
        parent::boot();

        $this->setAttributes([
            'class' => 'slugify',
        ]);
    }
}
