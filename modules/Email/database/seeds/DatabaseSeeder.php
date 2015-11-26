<?php

namespace KodiCMS\Email\database\seeds;

use Config;
use DB;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (Config::get('database.default') == 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
        } elseif (Config::get('database.default') == 'sqlite') {
            DB::statement('PRAGMA foreign_keys = OFF');
        }

        $this->call(EmailEventsTableSeeder::class);
        $this->call(EmailTemplatesTableSeeder::class);

        if (Config::get('database.default') == 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        } elseif (Config::get('database.default') == 'sqlite') {
            DB::statement('PRAGMA foreign_keys = ON');
        }
    }
}
