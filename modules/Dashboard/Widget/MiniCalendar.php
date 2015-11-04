<?php

namespace KodiCMS\Dashboard\Widget;

class MiniCalendar extends Decorator
{
    /**
     * @var array
     */
    protected $size = [
        'x'        => 3,
        'y'        => 1,
        'max_size' => [5, 1],
        'min_size' => [3, 1],
    ];

    /**
     * @var bool
     */
    protected $hasSettingsPage = true;

    /**
     * @var string
     */
    protected $frontendTemplate = 'dashboard::widgets.mini_calendar.template';

    /**
     * @var string
     */
    protected $settingsTemplate = 'dashboard::widgets.mini_calendar.settings';

    /**
     * @return string
     */
    public function getSettingFormat()
    {
        return array_get($this->settings, 'format', 'LLLL');
    }

    /**
     * @return array
     */
    public function prepareData()
    {
        return [
            'format' => $this->format,
        ];
    }

    /**
     * @return array
     */
    public function prepareSettingsData()
    {
        return [
            'formats' => [
                'LT'   => '8:30 PM',
                'LTS'  => '8:30:25 PM',
                'L'    => '09/04/1986',
                'LL'   => 'September 4 1986',
                'll'   => 'Sep 4 1986',
                'LLL'  => 'September 4 1986 8:30 PM',
                'lll'  => 'Sep 4 1986 8:30 PM',
                'LLLL' => 'Thursday, September 4 1986 8:30 PM',
                'llll' => 'Thu, Sep 4 1986 8:30 PM',
            ],
        ];
    }
}
