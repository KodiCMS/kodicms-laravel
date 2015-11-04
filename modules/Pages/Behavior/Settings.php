<?php

namespace KodiCMS\Pages\Behavior;

use KodiCMS\Pages\Contracts\BehaviorInterface;
use KodiCMS\Support\Traits\Settings as SettingsTrait;
use KodiCMS\Pages\Contracts\BehaviorSettingsInterface;

class Settings implements BehaviorSettingsInterface
{
    use SettingsTrait;

    /**
     * @var array
     */
    protected $settings = [];

    /**
     * @var BehaviorInterface
     */
    protected $behavior;

    /**
     * @param BehaviorInterface $behavior
     */
    public function __construct(BehaviorInterface $behavior)
    {
        $this->behavior = $behavior;
    }

    /**
     * @return string|null
     */
    public function render()
    {
        $template = $this->behavior->getSettingsTemplate();
        if (! is_null($template) and view()->exists($template)) {
            return view($template, [
                'settings' => $this,
                'behavior' => $this->behavior,
                'page'     => $this->behavior->getPage(),
            ])->render();
        }

        return;
    }
}
