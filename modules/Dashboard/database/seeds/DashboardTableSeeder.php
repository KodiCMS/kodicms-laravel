<?php namespace KodiCMS\Dashboard\database\seeds;

use Illuminate\Database\Seeder;
use KodiCMS\Dashboard\Dashboard;
use KodiCMS\Users\Model\User;

class DashboardTableSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		foreach (User::all() as $user)
		{
			\DB::table('user_meta')
				->insert([
					[
						'user_id' => $user->id,
						'key' => Dashboard::WIDGET_SETTINGS_KEY,
						'value' => '{"555608fdd8cd4":{"id":"555608fdd8cd4","type":"mini_calendar","settings":[],"parameters":[]},"55560bdf5e7bc":{"id":"55560bdf5e7bc","type":"cache_button","settings":[],"parameters":[]}}'
					],
					[
						'user_id' => $user->id,
						'key' => Dashboard::WIDGET_BLOCKS_KEY,
						'value' => '[{"col":3,"row":1,"sizex":3,"sizey":1,"max-sizex":5,"max-sizey":1,"min-sizex":3,"min-sizey":1,"widget_id":"555608fdd8cd4"},{"col":1,"row":1,"sizex":2,"sizey":1,"max-sizex":2,"max-sizey":1,"min-sizex":2,"min-sizey":1,"widget_id":"55560bdf5e7bc"}]'
					]
				]);
		}
	}
}