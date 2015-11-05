<?php

namespace KodiCMS\SleepingOwlAdmin\Filter;

use KodiCMS\SleepingOwlAdmin\AliasBinder;

/**
 * Class Filter.
 * @method static \KodiCMS\SleepingOwlAdmin\Filter\FilterCustom custom($name)
 * @method static \KodiCMS\SleepingOwlAdmin\Filter\FilterField field($name)
 * @method static \KodiCMS\SleepingOwlAdmin\Filter\FilterRelated related($name)
 * @method static \KodiCMS\SleepingOwlAdmin\Filter\FilterScope scope($name)
 */
class Filter extends AliasBinder
{
    /**
     * @var array
     */
    protected static $aliases = [];
}
