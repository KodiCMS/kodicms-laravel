<?php

namespace KodiCMS\SleepingOwlAdmin\ColumnFilters;

use KodiCMS\SleepingOwlAdmin\AliasBinder;

/**
 * @method static \KodiCMS\SleepingOwlAdmin\ColumnFilters\Text text()
 * @method static \KodiCMS\SleepingOwlAdmin\ColumnFilters\Date date()
 * @method static \KodiCMS\SleepingOwlAdmin\ColumnFilters\Select select()
 * @method static \KodiCMS\SleepingOwlAdmin\ColumnFilters\Range range()
 */
class ColumnFilter extends AliasBinder
{
    /**
     * Column filter class aliases.
     * @var string[]
     */
    protected static $aliases = [];
}
