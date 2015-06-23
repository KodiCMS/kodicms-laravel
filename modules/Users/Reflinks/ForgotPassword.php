<?php namespace KodiCMS\Users\Reflinks;

use Bus;
use KodiCMS\Email\Jobs\EmailSend;
use KodiCMS\Users\Model\UserReflink;
use KodiCMS\Users\Contracts\ReflinkInterface;

class ForgotPassword implements ReflinkInterface
{
	/**
	 * @param UserReflink $reflink
	 */
	public function generate(UserReflink $reflink)
	{
		Bus::dispatch(new EmailSend('user_request_password', [
			'code' => $reflink->code,
			'username' => $reflink->user->username,
			'email' => $reflink->user->email,
			'reflink' => $reflink->link()
		]));
	}

	/**
	 * @param UserReflink $reflink
	 */
	public function handle(UserReflink $reflink)
	{

	}

}