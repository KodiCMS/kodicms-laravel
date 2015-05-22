<?php namespace KodiCMS\Datasource\database\seeds;

use Illuminate\Database\Seeder;
use KodiCMS\Datasource\Model\Field;
use KodiCMS\Datasource\Model\Section;

class DatasourceTableSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 * @return void
	 */
	public function run()
	{
		$section = Section::create([
			'type' => 'test',
			'name' => 'Test section',
			'description' => 'Test description',
			'is_indexable' => false,
			'created_by_id' => 1,
			'settings' => []
		]);

		Field::create([
			'key' => 'other',
			'type' => 'string',
			'name' => 'Other',
			'settings' => [],
			'position' => 20
		]);
	}
}