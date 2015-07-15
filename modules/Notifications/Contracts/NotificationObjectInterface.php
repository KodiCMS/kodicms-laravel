<?php namespace KodiCMS\Notifications\Contracts;

interface NotificationObjectInterface {

	/**
	 * @param integer $id
	 */
	public function __construct($id);

	/**
	 * @return integer
	 */
	public function getId();
}