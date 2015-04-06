<?php namespace KodiCMS\Users\database\seeds;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

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

		$this->call('\KodiCMS\Users\database\seeds\RolesTableSeeder');
		$this->call('\KodiCMS\Users\database\seeds\UsersTableSeeder');
	}
}