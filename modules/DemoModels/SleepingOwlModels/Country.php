<?php

use Modules\DemoModels\Model\Contact;
use Modules\DemoModels\Model\Country;
use KodiCMS\SleepingOwlAdmin\Columns\Column;
use KodiCMS\SleepingOwlAdmin\FormItems\FormItem;
use KodiCMS\SleepingOwlAdmin\Model\ModelConfiguration;

SleepingOwlModule::registerModel(Country::class, function (ModelConfiguration $model) {
    $model->setTitle('Countries (orderable)');

    $model->onDisplay(function() {
        $display = SleepingOwlDisplay::table();

        $display->setAttribute('class', 'table-bordered table-success table-hover');

        $display->setApply(function ($query) {
            $query->orderBy('order', 'asc');
        });

        $display->setColumns([
            Column::string('id')
                ->setLabel('#')
                ->setWidth('30px'),
            Column::link('title')->setLabel('Title'),
            Column::count('contacts')
                ->setLabel('Contacts')
                ->setWidth('100px')
                ->setAttribute('class', 'text-center')
                ->append(
                    Column::filter('country_id')->setModel(new Contact)
                ),
            Column::order()
                ->setLabel('Order')
                ->setAttribute('class', 'text-center')
                ->setWidth('100px'),
        ]);

        return $display;
    });

    $model->onCreateAndEdit(function($id = null) {
        $form = SleepingOwlForm::form();
        $form->setItems([
            FormItem::text('title', 'Title')->required()->unique(),
        ]);
        return $form;
    });
})
    ->addMenuLink(Country::class)
    ->setIcon('globe');
