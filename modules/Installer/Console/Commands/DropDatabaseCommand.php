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

        $driver = Config::get('database.default');

        if ($driver == 'sqlite') {
            $query = "SELECT tbl_name as name FROM sqlite_master WHERE type = 'table' AND tbl_name NOT LIKE 'sqlite_%'";
        } else {
            // https://en.wikipedia.org/wiki/Information_schema
            $database = Config::get("database.$driver.database");
            $query = "SELECT table_name as name FROM information_schema.tables  WHERE table_schema LIKE '$database'";
        }

        $tables = DB::connection()->select($query);

        print_r($tables);

        if ($driver == 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
        } elseif ($driver == 'sqlite') {
            DB::statement('PRAGMA foreign_keys = OFF');
        }

        foreach ($tables as $table) {
            Schema::drop($table->name);
            $this->info("Table [{$table->name}] has been dropped.");
        }

        if ($driver == 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        } elseif ($driver == 'sqlite') {
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
