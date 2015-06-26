<?php namespace KodiCMS\Users\database\seeds;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use KodiCMS\Users\database\seeds\RolesTableSeeder;
use KodiCMS\Users\database\seeds\UsersTableSeeder;
use KodiCMS\Users\database\seeds\EmailEventsTableSeeder;
use KodiCMS\Users\database\seeds\EmailTemplatesTableSeeder;

class DatabaseSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Model::unguard();

		$this->call(RolesTableSeeder::class);
		$this->call(UsersTableSeeder::class);
		$this->call(EmailEventsTableSeeder::class);
		$this->call(EmailTemplatesTableSeeder::class);
	}
}