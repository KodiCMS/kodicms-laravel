<?php namespace KodiCMS\Widgets\Contracts;

interface WidgetDatabase extends Widget {

	/**
	 * @return int
	 */
	public function getId();

	/**
	 * @return bool
	 */
	public function isExists();
}