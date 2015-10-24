<?php namespace KodiCMS\CMS\Handlers\Events;

use DatabaseConfig;

class SettingsSave {

	/**
	 * Create a new event instance.
	 *
	 * @return void
	 */
	public function __construct()
	{

	}

	/**
	 * Handle the event.
	 *
	 * @return void
	 */
	public function handle(array $settings)
	{
		DatabaseConfig::save($settings);
	}
}
