<?php

namespace KodiCMS\SleepingOwlAdmin\Providers;

use KodiCMS\Support\ServiceProvider;
use KodiCMS\SleepingOwlAdmin\Display\SleepingOwlDisplay;

class DisplayServiceProvider extends ServiceProvider
{
    public function register()
    {
        SleepingOwlDisplay::register('datatables', \KodiCMS\SleepingOwlAdmin\Display\DisplayDatatables::class);
        SleepingOwlDisplay::register('datatablesAsync', \KodiCMS\SleepingOwlAdmin\Display\DisplayDatatablesAsync::class);
        SleepingOwlDisplay::register('tab', \KodiCMS\SleepingOwlAdmin\Display\DisplayTab::class);
        SleepingOwlDisplay::register('tabbed', \KodiCMS\SleepingOwlAdmin\Display\DisplayTabbed::class);
        SleepingOwlDisplay::register('table', \KodiCMS\SleepingOwlAdmin\Display\DisplayTable::class);
        SleepingOwlDisplay::register('tree', \KodiCMS\SleepingOwlAdmin\Display\DisplayTree::class);
    }
}
