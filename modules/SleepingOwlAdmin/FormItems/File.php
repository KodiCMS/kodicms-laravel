<?php

namespace KodiCMS\SleepingOwlAdmin\FormItems;

class File extends Image
{
    /**
     * @var string
     */
    protected $view = 'file';

    /**
     * @var string
     */
    protected static $route = 'uploadFile';

    /**
     * @return array
     */
    protected static function uploadValidationRules()
    {
        return [
            'file' => 'required',
        ];
    }
}
