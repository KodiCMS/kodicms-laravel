<?php

namespace KodiCMS\SleepingOwlAdmin\Providers;

use KodiCMS\Support\ServiceProvider;
use KodiCMS\SleepingOwlAdmin\Form\AdminForm;

class FormServiceProvider extends ServiceProvider
{
    public function register()
    {
        AdminForm::register('form', \KodiCMS\SleepingOwlAdmin\Form\FormDefault::class);
        AdminForm::register('tabbed', \KodiCMS\SleepingOwlAdmin\Form\FormTabbed::class);
        AdminForm::register('panel', \KodiCMS\SleepingOwlAdmin\Form\FormPanel::class);
    }
}
