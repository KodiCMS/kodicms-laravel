<?php

namespace KodiCMS\Email\Providers;

use Event;
use KodiCMS\Support\ServiceProvider;
use KodiCMS\Email\Console\Commands\QueueSendCommand;
use KodiCMS\Email\Console\Commands\QueueCleanCommand;

class ModuleServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->registerConsoleCommand([
            QueueSendCommand::class,
            QueueCleanCommand::class,
        ]);

        Event::listen('view.settings.bottom', function () {
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
