<?php

namespace KodiCMS\SleepingOwlAdmin\FormItems;

class Date extends BaseDateTime
{
    /**
     * @var string
     */
    protected $view = 'date';

    /**
     * @var string
     */
    protected $defaultConfigFormat = 'dateFormat';

    /**
     * @return string
     */
    public function getDefaultConfigFormat()
    {
        return $this->defaultConfigFormat;
    }

    /**
     * @param string $defaultConfigFormat
     *
     * @return Date
     */
    public function setDefaultConfigFormat($defaultConfigFormat)
    {
        $this->defaultConfigFormat = $defaultConfigFormat;

        return $this;
    }
}
