<?php namespace KodiCMS\Users\Contracts;

use KodiCMS\Users\Model\User;
use KodiCMS\Users\Model\UserReflink;

interface ReflinkInterface {

	/**
	 * @param UserReflink $reflink
	 */
	public function generate(UserReflink $reflink);

	/**
	 * @param UserReflink $reflink
	 */
	public function handle(UserReflink $reflink);
}