<?php

namespace KodiCMS\SleepingOwlAdmin\Providers;

use KodiCMS\Support\ServiceProvider;
use KodiCMS\SleepingOwlAdmin\Filter\Filter;

class FilterServiceProvider extends ServiceProvider
{
    public function register()
    {
        Filter::register('field', \KodiCMS\SleepingOwlAdmin\Filter\FilterField::class);
        Filter::register('scope', \KodiCMS\SleepingOwlAdmin\Filter\FilterScope::class);
        Filter::register('custom', \KodiCMS\SleepingOwlAdmin\Filter\FilterCustom::class);
        Filter::register('related', \KodiCMS\SleepingOwlAdmin\Filter\FilterRelated::class);
    }
}
