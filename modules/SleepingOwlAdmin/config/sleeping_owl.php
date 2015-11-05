<?php

return [
    /*
     * Admin title
     * Displays in page title and header
     */
    'title'                 => 'Sleeping Owl administrator',
    'prefix'                => backend_url_segment(),
    'middleware'            => null,

    /*
     * Directory to upload images to (relative to public directory)
     */
    'imagesUploadDirectory' => 'images/uploads',

    /*
     * Template to use
     */
    'template'              => 'KodiCMS\SleepingOwlAdmin\Templates\TemplateDefault',

    /*
     * Default date and time formats
     */
    'datetimeFormat'        => 'd.m.Y H:i',
    'dateFormat'            => 'd.m.Y',
    'timeFormat'            => 'H:i',
];
