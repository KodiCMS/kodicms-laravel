<?php

namespace Plugins\butschster\News\Model\FieldCollections;

use KodiCMS\Support\Model\Fields\WYSIWYGField;
use KodiCMS\Support\Model\Contracts\ModelFieldsInterface;

class NewsContentFieldCollection implements ModelFieldsInterface
{
    /**
     * @return array
     */
    public function fields()
    {
        return [
            (new WYSIWYGField('content'))->setTitle(trans('butschster:news::core.field.content')),
            (new WYSIWYGField('description'))->setTitle(trans('butschster:news::core.field.description')),
        ];
    }
}
