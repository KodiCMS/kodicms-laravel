<?php namespace KodiCMS\Widgets\database\seeds;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use KodiCMS\Widgets\database\seeds\WidgetTableSeeder;

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
		$this->call(WidgetTableSeeder::class);
	}
}