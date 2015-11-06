<?php

use KodiCMS\Users\Model\User;

SleepingOwlModule::addMenuLink(User::class)->setIcon('users');

SleepingOwlModule::getModel(User::class)
    ->setTitle('User')
    ->onDisplay(function () {
        return SleepingOwlDisplay::table();
    })
    ->onCreateAndEdit(function () {
        return SleepingOwlForm::form();
    });
