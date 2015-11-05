<?php

namespace KodiCMS\Dashboard\database\seeds;

use DB;
use KodiCMS\Users\Model\User;
use Illuminate\Database\Seeder;
use KodiCMS\Dashboard\Dashboard;

class DashboardTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('user_meta')->truncate();

        foreach (User::all() as $user) {
            DB::table('user_meta')->insert([
                [
                    'user_id' => $user->id,
                    'key'     => Dashboard::WIDGET_SETTINGS_KEY,
                    'value'   => '{"555608fdd8cd4":{"id":"555608fdd8cd4","type":"mini_calendar","settings":[],"parameters":[]},"55560bdf5e7bc":{"id":"55560bdf5e7bc","type":"cache_button","settings":[],"parameters":[]},"5577076067b03":{"id":"5577076067b03","type":"kodicms_rss","settings":[],"parameters":[]},"55a6661472f0d":{"id":"55a6661472f0d","type":"profiler","settings":[],"parameters":[]}}',
                ],
                [
                    'user_id' => $user->id,
                    'key'     => Dashboard::WIDGET_BLOCKS_KEY,
                    'value'   => '[{"col":3,"row":1,"sizex":3,"sizey":1,"max-sizex":5,"max-sizey":1,"min-sizex":3,"min-sizey":1,"widget_id":"555608fdd8cd4"},{"col":1,"row":1,"sizex":2,"sizey":1,"max-sizex":2,"max-sizey":1,"min-sizex":2,"min-sizey":1,"widget_id":"55560bdf5e7bc"},{"col":1,"row":2,"sizex":5,"sizey":3,"max-sizex":5,"max-sizey":10,"min-sizex":3,"min-sizey":2,"widget_id":"5577076067b03"},{"col":1,"row":5,"sizex":5,"sizey":2,"max-sizex":6,"max-sizey":2,"min-sizex":3,"min-sizey":2,"widget_id":"55a6661472f0d"}]',
                ],
            ]);
        }
    }
}
