<?php

use Modules\DemoModels\Model\Company;
use Modules\DemoModels\Model\Country;
use Modules\DemoModels\Model\Contact2;
use KodiCMS\SleepingOwlAdmin\Filter\Filter;
use KodiCMS\SleepingOwlAdmin\Columns\Column;
use KodiCMS\SleepingOwlAdmin\FormItems\FormItem;
use KodiCMS\SleepingOwlAdmin\Model\ModelConfiguration;

SleepingOwlModule::registerModel(Contact2::class, function (ModelConfiguration $model) {
    $model->setTitle('Contacts v.2')->setAlias('alternate/contacts');

    // Display
    $model->onDisplay(function () {
        $display = SleepingOwlDisplay::table();
        $display->setWith('country', 'companies');
        $display->setFilters([
            Filter::related('country_id')->setModel(Country::class)
        ]);

        $display->setColumns([
            Column::image('photo')->setLabel('Photo')
                ->setWidth('100px'),
            Column::link('fullName')->setLabel('Name')
                ->setWidth('200px'),
            Column::datetime('birthday')->setLabel('Birthday')->setFormat('d.m.Y')
                ->setWidth('150px')
                ->setAttribute('class', 'text-center'),
            Column::string('country.title')->setLabel('Country')->append(Column::filter('country_id')),
            Column::lists('companies.title')->setLabel('Companies'),
        ]);

        return $display;
    });

    // Create And Edit
    $model->onCreateAndEdit(function($id = null) {
        $form = SleepingOwlForm::form();

        $form->setItems(
            FormItem::columns()
                ->addColumn(function() {
                    return [
                        FormItem::text('firstName', 'First Name')->required(),
                        FormItem::text('lastName', 'Last Name')->required(),
                        FormItem::text('phone', 'Phone'),
                        FormItem::text('address', 'Address'),
                    ];
                })->addColumn(function() {
                    return [
                        FormItem::image('photo', 'Photo'),
                        FormItem::date('birthday', 'Birthday')->setFormat('d.m.Y'),
                    ];
                })->addColumn(function() {
                    return [
                        FormItem::select('country_id', 'Country')->setModelForOptions(new Country)->setDisplay('title'),
                        FormItem::multiselect('companies', 'Companies')->setModelForOptions(new Company)->setDisplay('title'),
                        FormItem::textarea('comment', 'Comment'),
                    ];
                })
        );

        return $form;
    });

})
    ->addMenuLink(Contact2::class)
    ->setIcon('fax');
