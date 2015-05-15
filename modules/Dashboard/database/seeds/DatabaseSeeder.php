<?php namespace KodiCMS\Dashboard\database\seeds;

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

		$this->call('\KodiCMS\Dashboard\database\seeds\DashboardTableSeeder');
	}
}