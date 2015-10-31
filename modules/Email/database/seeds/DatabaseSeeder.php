<?php
namespace KodiCMS\Email\database\seeds;

use DB;
use Illuminate\Database\Seeder;
use KodiCMS\Email\Model\EmailEvent;
use KodiCMS\Email\Model\EmailTemplate;

class DatabaseSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        EmailEvent::truncate();
        EmailTemplate::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $this->call(EmailEventsTableSeeder::class);
        $this->call(EmailTemplatesTableSeeder::class);
    }
}