<?php

namespace KodiCMS\SleepingOwlAdmin\Providers;

use KodiCMS\Support\ServiceProvider;
use KodiCMS\SleepingOwlAdmin\Columns\Column;

class ColumnServiceProvider extends ServiceProvider
{
    public function register()
    {
        Column::register('action', \KodiCMS\SleepingOwlAdmin\Columns\Column\Action::class);
        Column::register('checkbox', \KodiCMS\SleepingOwlAdmin\Columns\Column\Checkbox::class);
        Column::register('control', \KodiCMS\SleepingOwlAdmin\Columns\Column\Control::class);
        Column::register('count', \KodiCMS\SleepingOwlAdmin\Columns\Column\Count::class);
        Column::register('custom', \KodiCMS\SleepingOwlAdmin\Columns\Column\Custom::class);
        Column::register('datetime', \KodiCMS\SleepingOwlAdmin\Columns\Column\DateTime::class);
        Column::register('filter', \KodiCMS\SleepingOwlAdmin\Columns\Column\Filter::class);
        Column::register('image', \KodiCMS\SleepingOwlAdmin\Columns\Column\Image::class);
        Column::register('lists', \KodiCMS\SleepingOwlAdmin\Columns\Column\Lists::class);
        Column::register('order', \KodiCMS\SleepingOwlAdmin\Columns\Column\Order::class);
        Column::register('string', \KodiCMS\SleepingOwlAdmin\Columns\Column\String::class);
        Column::register('treeControl', \KodiCMS\SleepingOwlAdmin\Columns\Column\TreeControl::class);
        Column::register('url', \KodiCMS\SleepingOwlAdmin\Columns\Column\Url::class);
    }
}
