<?php namespace KodiCMS\Notifications\Types;

use KodiCMS\CMS\Helpers\Updater;
use KodiCMS\Notifications\Contracts\NotificationTypeInterface;

class CMSNewVersionNotification implements NotificationTypeInterface
{
	/**
	 * @var bool
	 */
	protected $newVersion = false;

	/**
	 * @var integer
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
	public function getTitle()
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
		if ($this->newVersion)
		{
			return trans('cms::core.messages.new_version', ['version' => $this->version]);
		}
		else
		{
			return trans('cms::core.messages.no_new_version');
		}
	}

	/**
	 * Get the instance as an array.
	 *
	 * @return array
	 */
	public function toArray()
	{
		return [
			'title' => $this->getTitle(),
			'icon' => $this->getIcon(),
			'color' => $this->getColor(),
			'message' => $this->getMessage(),
			'newVersion' => $this->newVersion,
			'sent_on' => date('Y-m-d H:i:d')
		];
	}
}