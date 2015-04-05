<?php namespace KodiCMS\Pages\Database\Seeds;

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

		$this->call('\KodiCMS\Pages\Database\Seeds\PagesTableSeeder');
	}
}