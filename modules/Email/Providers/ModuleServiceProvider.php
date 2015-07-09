<?php namespace KodiCMS\Email\Providers;

use Event;
use KodiCMS\Email\Console\Commands\QueueSend;
use KodiCMS\Email\Console\Commands\QueueClean;
use KodiCMS\ModulesLoader\Providers\ServiceProvider;

class ModuleServiceProvider extends ServiceProvider
{

	public function register()
	{
		$this->registerConsoleCommand('email.queue-send', QueueSend::class);
		$this->registerConsoleCommand('email.queue-clean', QueueClean::class);

		Event::listen('view.settings.bottom', function ()
		{
			$drivers = [
				'mail'     => 'Native',
				'smtp'     => 'SMTP',
				'sendmail' => 'Sendmail',
				'mailgun'  => 'Mailgun',
				'mandrill' => 'Mandrill',
				'log'      => 'Log',
			];

			echo view('email::email.settings', compact('drivers'))->render();
		});
	}

}