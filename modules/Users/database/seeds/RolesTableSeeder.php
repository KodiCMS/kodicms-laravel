<?php namespace KodiCMS\Users\Database\Seeds;

use Illuminate\Database\Seeder;
use KodiCMS\Users\Model\UserRole;

class RolesTableSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		\DB::table('roles')->truncate();

		$roles = [
			[
				'name' => 'administrator',
				'description' => 'Administrative user, has access to everything.'
			],
			[
				'name' => 'developer',
				'description' => ''
			]
		];

		foreach ($roles as $data)
		{
			UserRole::create($data);
		}
	}
}
