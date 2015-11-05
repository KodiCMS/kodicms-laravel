<?php

namespace KodiCMS\Datasource\Console\Commands;

use Illuminate\Console\Command;
use KodiCMS\Datasource\DatasourceManager;

class DatasourceMigrate extends Command
{
    /**
     * The console command name.
     */
    protected $name = 'cms:datasource:migrate';

    /**
     * Execute the console command.
     */
    public function fire()
    {
        $this->output->writeln('<info>Migrating KodiCMS datasource sections...</info>');

        // TODO: реализовать миграцию разделов
        DatasourceManager::getAvailableSections();
    }
}
