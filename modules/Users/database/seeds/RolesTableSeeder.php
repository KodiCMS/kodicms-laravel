<?php namespace KodiCMS\Users\database\seeds;

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
				'name' => 'login',
				'description' => 'Login privileges, granted after account confirmation.'
			],
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
