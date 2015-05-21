<?php namespace KodiCMS\Email\Providers;

use Event;
use KodiCMS\CMS\Providers\ServiceProvider;

class ModuleServiceProvider extends ServiceProvider
{

	public function register()
	{
		$this->registerConsoleCommand('email.queue-send', 'KodiCMS\Email\Console\Commands\QueueSend');
		$this->registerConsoleCommand('email.queue-clean', 'KodiCMS\Email\Console\Commands\QueueClean');

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