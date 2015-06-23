<?php namespace KodiCMS\Users\Jobs;

use KodiCMS\Users\Model\User;
use KodiCMS\Users\Reflinks\ForgotPassword;

class ReflinkForgotPassword extends ReflinkGenerator {

	/**
	 * @var User
	 */
	protected $user;

	/**
	 * @var array
	 */
	protected $properties = [];

	/**
	 * @param string $email
	 * @param array  $properties
	 */
	public function __construct($email, array $properties = [])
	{
		$this->user = User::where('email', $email)->first();
		$this->properties = $properties;

		$this->type = new ForgotPassword;
	}
}