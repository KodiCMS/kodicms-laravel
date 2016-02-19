<?php

use KodiCMS\Users\Model\User;
use KodiCMS\Users\Model\UserRole;
use KodiCMS\SleepingOwlAdmin\Filter\Filter;
use KodiCMS\SleepingOwlAdmin\Columns\Column;
use KodiCMS\SleepingOwlAdmin\Filter\FilterBase;
use KodiCMS\SleepingOwlAdmin\FormItems\FormItem;
use KodiCMS\SleepingOwlAdmin\Display\DisplayTabbed;
use KodiCMS\SleepingOwlAdmin\Model\ModelConfiguration;

SleepingOwlModule::registerModel(User::class, function (ModelConfiguration $model) {
        $model->setTitle('User')
            ->onDisplay(function () {
                $display = SleepingOwlDisplay::tabbed();

                $display->setTabs(function (DisplayTabbed $tabbed) {

                    $tabbed->appendDisplay(
                        SleepingOwlDisplay::table()
                            ->setFilters([
                                Filter::field('name')
                                    ->setOperator(FilterBase::BEGINS_WITH)
                                    ->setValue('ad')
                            ])
                            ->setColumns([
                                Column::link('name')->setLabel('name'),
                                Column::lists('roles.name')->setLabel('Roles')->setWidth('300px'),
                                Column::email('email')->setLabel('E-mail')->setWidth('200px'),
                            ]), 'First Tab');

                    $tabbed->appendDisplay(SleepingOwlDisplay::table()->setColumns([
                        Column::link('username')->setLabel('Username'),
                    ]), 'Second Tab');
                });

                return $display;
            })->onCreateAndEdit(function () {
                $form = SleepingOwlForm::form();
                $form->setItems(
                    FormItem::columns()->addColumn(function() {
                        return [
                            FormItem::wysiwyg('name', 'Username', 'ace')->required(),
                            FormItem::text('email', 'E-mail')->required()->addValidationRule('email'),
                            FormItem::timestamp('created_at', 'Date creation'),
                            FormItem::multiselect('roles', 'Roles')->setModelForOptions(new UserRole)->setDisplay('name'),
                        ];
                    })
                );

                return $form;
            });
    })
    ->addMenuLink(User::class)
    ->setIcon('users');
