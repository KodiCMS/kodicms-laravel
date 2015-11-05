<?php

namespace KodiCMS\SleepingOwlAdmin\Providers;

use KodiCMS\Support\ServiceProvider;
use KodiCMS\SleepingOwlAdmin\ColumnFilters\ColumnFilter;

class ColumnFilterServiceProvider extends ServiceProvider
{
    public function register()
    {
        ColumnFilter::register('text', \KodiCMS\SleepingOwlAdmin\ColumnFilters\Text::class);
        ColumnFilter::register('date', \KodiCMS\SleepingOwlAdmin\ColumnFilters\Date::class);
        ColumnFilter::register('range', \KodiCMS\SleepingOwlAdmin\ColumnFilters\Range::class);
        ColumnFilter::register('select', \KodiCMS\SleepingOwlAdmin\ColumnFilters\Select::class);
    }
}
