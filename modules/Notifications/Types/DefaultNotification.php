<?php namespace KodiCMS\Notifications\Types;

use KodiCMS\Notifications\Contracts\NotificationTypeInterface;

class DefaultNotification implements NotificationTypeInterface
{
	/**
	 * @return string
	 */
	public function getTitle()
	{
		return 'information';
	}

	/**
	 * @return string
	 */
	public function getIcon()
	{
		return 'exclamation-triangle';
	}

	/**
	 * @return string
	 */
	public function getColor()
	{
		return 'info';
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
			'color' => $this->getColor()
		];
	}
}