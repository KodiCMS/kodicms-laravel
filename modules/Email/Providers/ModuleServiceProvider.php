<?php namespace KodiCMS\Email\Providers;

use Event;
use KodiCMS\CMS\Providers\ServiceProvider;
use Validator;

class ModuleServiceProvider extends ServiceProvider
{

	public function register()
	{
		Event::listen('view.settings.bottom', function ()
		{
			$drivers = [
				'mail'     => 'Native',
				'smtp'     => 'SMTP',
				'sendmail' => 'Sendmail',
				'mailgun'  => 'Mailgun',
				'mandrill' => 'Mandrill',
			];
			echo view('email::email.settings', compact('drivers'))->render();
		});

	}

}