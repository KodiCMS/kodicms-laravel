<?php namespace KodiCMS\Datasource\database\seeds;

use DatasourceManager;
use Illuminate\Database\Seeder;
use KodiCMS\Datasource\Fields\String;
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

		DatasourceManager::addNewField($section->toSection(), new String(null, [
			'key' => 'test',
			'name' => 'test'
		]));
	}
}