<?php

return [
    'tags' => [
        'class'             => Plugins\butschster\DSTags\Fields\Tags::class,
        'title'             => trans('butschster:dstags::core.fields.tags.title'),
        'edit_template'     => 'butschster:dstags::field.edit_template',
        'document_template' => 'butschster:dstags::field.document_template',
        'category'          => 'Source',
    ],
];
