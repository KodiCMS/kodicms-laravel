<?php namespace KodiCMS\Users\Reflinks\Generators;

use Bus;
use Password;
use KodiCMS\Users\Model\User;
use KodiCMS\Email\Jobs\EmailSend;
use KodiCMS\Users\Model\UserReflink;
use KodiCMS\Users\Exceptions\ReflinkException;
use KodiCMS\Users\Contracts\ReflinkGeneratorInterface;
use KodiCMS\Users\Reflinks\Handlers\ForgotPasswordHandler;

class ForgotPasswordGenerator implements ReflinkGeneratorInterface
{
	/**
	 * @var string
	 */
	protected $email;

	/**
	 * @var array
	 */
	protected $properties = [];

	/**
	 * @param string $email
	 * @param array  $properties
	 *
	 * @throws ReflinkException
	 */
	public function __construct($email, array $properties = [])
	{
		$this->email = $email;
		$this->properties = $properties;
	}

	/**
	 * @return string
	 */
	public function getHandlerClass()
	{
		return ForgotPasswordHandler::class;
	}

	/**
	 * @return User
	 * @throws ReflinkException
	 */
	public function getUser()
	{
		if (is_null($user = User::where('email', $this->email)->first()))
		{
			throw new ReflinkException(trans(Password::INVALID_USER));
		}

		return $user;
	}

	/**
	 * @return array
	 */
	public function getProperties()
	{
		return $this->properties;
	}

	/**
	 * @param UserReflink $reflink
	 */
	public function tokenGenerated(UserReflink $reflink)
	{
		Bus::dispatch(new EmailSend('user_request_password', [
			'code' => $reflink->token,
			'username' => $reflink->user->username,
			'email' => $reflink->user->email,
			'reflink' => $reflink->linkToken()
		]));
	}
}