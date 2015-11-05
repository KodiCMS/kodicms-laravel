<?php

use KodiCMS\Users\Model\User;

SleepingOwlAdmin::addMenuLink(User::class);

SleepingOwlAdmin::getModel(User::class)
    ->title('User')
    ->display(function () {
        return AdminDisplay::table();
    })
    ->createAndEdit(function () {
        return AdminForm::form();
    });
