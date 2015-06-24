<?php namespace KodiCMS\Email\database\seeds;

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
		DB::statement('SET FOREIGN_KEY_CHECKS=0');
		DB::table('email_templates')->truncate();
		DB::table('email_events')->truncate();
		DB::statement('SET FOREIGN_KEY_CHECKS=1');
	}
}