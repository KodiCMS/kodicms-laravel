<?php

use Modules\DemoModels\Model\Company;
use KodiCMS\SleepingOwlAdmin\Columns\Column;
use KodiCMS\SleepingOwlAdmin\FormItems\FormItem;
use KodiCMS\SleepingOwlAdmin\Model\ModelConfiguration;

SleepingOwlModule::registerModel(Company::class, function (ModelConfiguration $model) {
    $model->setTitle('Companies');

    // Display
    $model->onDisplay(function () {
        return SleepingOwlDisplay::table()->setColumns([
            Column::link('title')->setLabel('Title')->setWidth('400px'),
            Column::string('address')->setLabel('Address')->setAttribute('class', 'text-muted'),
        ]);
    });

    // Create And Edit
    $model->onCreateAndEdit(function() {
        return SleepingOwlForm::form()->setItems([
            FormItem::hidden('contact_id'),
            FormItem::text('title', 'Title')->required()->unique(),
            FormItem::text('address', 'Address'),
            FormItem::text('phone', 'Phone'),
        ]);
    });
})
->addMenuLink(Company::class)
->setIcon('bank');
