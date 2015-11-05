<?php

namespace KodiCMS\SleepingOwlAdmin\Providers;

use KodiCMS\Support\ServiceProvider;
use KodiCMS\SleepingOwlAdmin\Display\AdminDisplay;

class DisplayServiceProvider extends ServiceProvider
{
    public function register()
    {
        AdminDisplay::register('datatables', \KodiCMS\SleepingOwlAdmin\Display\DisplayDatatables::class);
        AdminDisplay::register('datatablesAsync', \KodiCMS\SleepingOwlAdmin\Display\DisplayDatatablesAsync::class);
        AdminDisplay::register('tab', \KodiCMS\SleepingOwlAdmin\Display\DisplayTab::class);
        AdminDisplay::register('tabbed', \KodiCMS\SleepingOwlAdmin\Display\DisplayTabbed::class);
        AdminDisplay::register('table', \KodiCMS\SleepingOwlAdmin\Display\DisplayTable::class);
        AdminDisplay::register('tree', \KodiCMS\SleepingOwlAdmin\Display\DisplayTree::class);
    }
}
