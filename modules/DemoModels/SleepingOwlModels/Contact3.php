<?php

use Modules\DemoModels\Model\Country;
use Modules\DemoModels\Model\Company;
use Modules\DemoModels\Model\Contact;
use Modules\DemoModels\Model\Contact3;
use KodiCMS\SleepingOwlAdmin\Filter\Filter;
use KodiCMS\SleepingOwlAdmin\Columns\Column;
use KodiCMS\SleepingOwlAdmin\FormItems\FormItem;
use KodiCMS\SleepingOwlAdmin\Model\ModelConfiguration;

SleepingOwlModule::registerModel(Contact3::class, function (ModelConfiguration $model) {
    $model->setTitle('Contacts v.3')->setAlias('super/contacts');

    // Display
    $model->onDisplay(function () {
        $display = SleepingOwlDisplay::table();
        $display->setWith('country', 'companies');
        $display->setFilters([
            Filter::related('country_id')->setModel(Country::class)
        ]);

        $display->setColumns([
            Column::image('photo')->setLabel('Photo'),
            Column::link('fullName')->setLabel('Name'),
            Column::datetime('birthday')->setLabel('Birthday')->setFormat('d.m.Y'),
            Column::string('country.title')->setLabel('Country')->append(Column::filter('country_id')),
            Column::lists('companies.title')->setLabel('Companies'),
        ]);

        return $display;
    });

    // Create And Edit
    $model->onCreateAndEdit(function($id = null) {
        $display = SleepingOwlDisplay::tabbed();

        $display->setTabs(function() use ($id) {
            $tabs = [];

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
                            FormItem::wysiwyg('comment', 'Comment')->setEditor('ckeditor'),
                        ];
                    })
            );

            $tabs[] = SleepingOwlDisplay::tab($form)->setLabel('Main Form')->setActive(true);

            if (! is_null($id)) {
                $instance = Contact::find($id);

                if (! is_null($instance->country_id)) {
                    if (! is_null($country = SleepingOwlModule::getModel(Country::class)->fireFullEdit($instance->country_id))) {
                        $tabs[] = SleepingOwlDisplay::tab($country)->setLabel('Form from Related Model (Country)');
                    }
                }

                $companies = SleepingOwlModule::getModel(Company::class)->fireDisplay();

                $companies->appendScope(['withContact', $id]);
                $companies->setParameter('contact_id', $id);

                $tabs[] = SleepingOwlDisplay::tab($companies)->setLabel('Display from Related Model (Companies)');
            }
            return $tabs;
        });


        return $display;
    });
})
    ->addMenuLink(Contact3::class)
    ->setIcon('credit-card');
