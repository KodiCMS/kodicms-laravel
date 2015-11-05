<?php

namespace KodiCMS\SleepingOwlAdmin\FormItems;

class Timestamp extends BaseDateTime
{
    /**
     * @var string
     */
    protected $view = 'timestamp';

    /**
     * @var string
     */
    protected $defaultConfigFormat = 'datetimeFormat';
}
