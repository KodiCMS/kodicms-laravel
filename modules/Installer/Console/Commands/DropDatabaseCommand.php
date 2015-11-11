<?php

namespace KodiCMS\Installer\Console\Commands;

use Config;
use DB;
use Schema;
use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;
use Symfony\Component\Console\Input\InputOption;

class DropDatabaseCommand extends Command
{
    use ConfirmableTrait;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'db:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Drop all database tables. (Only CLI)';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        if (php_sapi_name() !== 'cli') {
            $this->comment('Command Cancelled!');

            return false;
        }

        if (! $this->confirmToProceed()) {
            return;
        }

        $tables = Schema::getConnection()->getDoctrineSchemaManager()->listTableNames();

        if (Config::get('database.default') == 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
        } else if (Config::get('database.default') == 'sqlite') {
            DB::statement('PRAGMA foreign_keys = OFF');
        }

        foreach ($tables as $table) {
            Schema::drop($table);
            $this->info("Table [{$table}] has been dropped.");
        }

        if (Config::get('database.default') == 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        } else if (Config::get('database.default') == 'sqlite') {
            DB::statement('PRAGMA foreign_keys = ON');
        }
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['force', null, InputOption::VALUE_NONE, 'Force the operation to run when in production.'],
        ];
    }
}
