<?php

use Modules\DemoModels\Model\Contact;
use Modules\DemoModels\Model\Country;
use KodiCMS\SleepingOwlAdmin\Filter\Filter;
use KodiCMS\SleepingOwlAdmin\Columns\Column;
use KodiCMS\SleepingOwlAdmin\Model\ModelConfiguration;

SleepingOwlModule::registerModel(Contact::class, function (ModelConfiguration $model) {
    $model->setTitle('Contacts');

    $model->onDisplay(function () {
        $display = SleepingOwlDisplay::table();


        $display->setAttribute('class', 'table-info table-hover');

        $display->setWith('country', 'companies');
        $display->setFilters([
            Filter::related('country_id')->setModel(Country::class)
        ]);

        $display->setColumns([
            Column::image('photo')
                ->setLabel('Photo<br/><small>(image)</small>')
                ->setWidth('100px'),
            Column::string('fullName')
                ->setLabel('Name<br/><small>(string with accessor)</small>')
                ->setWidth('200px'),
            Column::datetime('birthday')
                ->setLabel('Birthday<br/><small>(datetime)</small>')
                ->setWidth('150px')
                ->setAttribute('class', 'text-center')
                ->setFormat('d.m.Y'),
            Column::string('country.title')
                ->setLabel('Country<br/><small>(string from related model)</small>')
                 ->append(
                    Column::filter('country_id')
                ),
            Column::count('companies')
                ->setLabel('Companies<br/><small>(count)</small>')
                ->setAttribute('class', 'text-center')
                ->setWidth('50px'),
            Column::lists('companies.title')->setLabel('Companies<br/><small>(lists)</small>'),
            Column::custom()->setLabel('Has Photo?<br/><small>(custom)</small>')->setCallback(function ($instance) {
                return $instance->photo ? '<i class="fa fa-check"></i>' : '<i class="fa fa-minus"></i>';
            })
                ->setAttribute('class', 'text-center')
                ->setWidth('50px'),
        ]);

        return $display;
    });
})->addMenuLink(Contact::class)->setIcon('user');
