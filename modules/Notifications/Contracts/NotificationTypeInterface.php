<?php namespace KodiCMS\Notifications\Contracts;

use Illuminate\Contracts\Support\Arrayable;

interface NotificationTypeInterface extends Arrayable {

	/**
	 * @return string
	 */
	public function getTitle();

	/**
	 * @return string
	 */
	public function getIcon();

	/**
	 * @return string
	 */
	public function getColor();
}