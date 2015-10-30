<?php
namespace KodiCMS\Installer\Console\Commands;

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

        if ( ! $this->confirmToProceed()) {
            return;
        }

        $tables = [];

        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        foreach (DB::select('SHOW TABLES') as $k => $v) {
            $tables[] = array_values((array) $v)[0];
        }

        foreach ($tables as $table) {
            Schema::drop($table);
            $this->info("Table [{$table}] has been dropped.");
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
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