<?php

namespace KodiCMS\Notifications\Types;

use KodiCMS\CMS\Helpers\Updater;

class CMSNewVersionNotification extends DefaultNotification
{
    /**
     * @var bool
     */
    protected $newVersion = false;

    /**
     * @var int
     */
    protected $version;

    /**
     * @param Updater $updater
     */
    public function __construct(Updater $updater)
    {
        $this->version = $updater->getRemoteVersion();
        $this->newVersion = $updater->hasNewVersion();
    }

    /**
     * @return string
     */
    public function getId()
    {
        return false;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return 'Update';
    }

    public function getIcon()
    {
        return 'cloud-download';
    }

    /**
     * @return string
     */
    public function getColor()
    {
        return 'warning';
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        if ($this->newVersion) {
            return trans('cms::core.messages.new_version', ['version' => $this->version]);
        } else {
            return trans('cms::core.messages.no_new_version');
        }
    }

    /**
     * @return Carbon
     */
    public function getDate()
    {
        return date('Y-m-d H:i:d');
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return array_merge(parent::toArray(), [
            'newVersion' => $this->newVersion,
        ]);
    }
}
