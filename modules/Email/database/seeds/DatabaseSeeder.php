<?php namespace KodiCMS\Email\database\seeds;

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

		$this->call('\KodiCMS\Email\database\seeds\EmailTypesTableSeeder');
		$this->call('\KodiCMS\Email\database\seeds\EmailTemplatesTableSeeder');
	}
}