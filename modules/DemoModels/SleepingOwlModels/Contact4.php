<?php

use KodiCMS\SleepingOwlAdmin\ColumnFilters\ColumnFilter;
use Modules\DemoModels\Model\Country;
use Modules\DemoModels\Model\Contact4;
use KodiCMS\SleepingOwlAdmin\Filter\Filter;
use KodiCMS\SleepingOwlAdmin\Columns\Column;
use KodiCMS\SleepingOwlAdmin\Model\ModelConfiguration;

SleepingOwlModule::registerModel(Contact4::class, function (ModelConfiguration $model) {
    $model->setTitle('Contacts v.4')->setAlias('modern/contacts');

    // Display
    $model->onDisplay(function () {
        $display = SleepingOwlDisplay::datatables()->setAttribute('class', 'table-primary');
        $display->setWith('country', 'companies');
        $display->setFilters([
            Filter::related('country_id')->setModel(Country::class)
        ]);
        $display->setOrder([[1, 'asc']]);

        $display->setColumns([
            Column::image('photo')->setLabel('Photo')
                ->setWidth('100px'),
            Column::link('fullName')->setLabel('Name')
                ->setWidth('200px'),
            Column::string('height')->setLabel('Height'),
            Column::datetime('birthday')->setLabel('Birthday')->setFormat('d.m.Y')
                ->setWidth('150px')
                ->setAttribute('class', 'text-center'),
            Column::string('country.title')->setLabel('Country')->append(Column::filter('country_id')),
            Column::lists('companies.title')->setLabel('Companies')
        ]);

        $display->setColumnFilters([
            null,
            ColumnFilter::text()->setPlaceholder('Full Name'),
            ColumnFilter::range()->setFrom(
                ColumnFilter::text()->setPlaceholder('From')
            )->setTo(
                ColumnFilter::text()->setPlaceholder('To')
            ),
            ColumnFilter::range()->setFrom(
                ColumnFilter::date()->setPlaceholder('From Date')->setFormat('d.m.Y')
            )->setTo(
                ColumnFilter::date()->setPlaceholder('To Date')->setFormat('d.m.Y')
            ),
            ColumnFilter::select()->setPlaceholder('Country')->setModel(new Country)->setDisplay('title'),
            ColumnFilter::text()->setPlaceholder('Companies'),
        ]);

        return $display;
    });

})
    ->addMenuLink(Contact4::class)
    ->setIcon('credit-card');
