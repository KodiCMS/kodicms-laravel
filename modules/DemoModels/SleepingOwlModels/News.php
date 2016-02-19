<?php

use Modules\DemoModels\Model\News;
use KodiCMS\SleepingOwlAdmin\Columns\Column;
use KodiCMS\SleepingOwlAdmin\FormItems\FormItem;
use KodiCMS\SleepingOwlAdmin\Model\ModelConfiguration;

SleepingOwlModule::registerModel(News::class, function (ModelConfiguration $model) {
    $model->setTitle('News');

    // Display
    $model->onDisplay(function () {
        return SleepingOwlDisplay::table()->setApply(function($query) {
            $query->orderBy('date', 'desc');
        })->setColumns([
            Column::link('title')->setLabel('Title'),
            Column::datetime('date')->setLabel('Date')->setFormat('d.m.Y')->setWidth('150px'),
            Column::custom()->setLabel('Published')->setCallback(function ($instance) {
                return $instance->published ? '<i class="fa fa-check"></i>' : '<i class="fa fa-minus"></i>';
            })->setWidth('50px')->setAttribute('class', 'text-center'),
        ]);
    });

    // Create And Edit
    $model->onCreateAndEdit(function() {
        return SleepingOwlForm::form()->setItems([
            FormItem::text('title', 'Title')->required(),
            FormItem::date('date', 'Date')->required()->setFormat('d.m.Y'),
            FormItem::checkbox('published', 'Published'),
            FormItem::ckeditor('text', 'Text'),
        ]);
    });
})
    ->addMenuLink(News::class)
    ->setIcon('newspaper-o');
