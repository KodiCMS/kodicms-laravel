<?php namespace KodiCMS\Users\database\seeds;

use Illuminate\Database\Seeder;
use KodiCMS\Users\Model\User;

class UsersTableSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		\DB::table('users')->truncate();

		$user = User::create([
			'email' => 'admin@site.com',
			'password' => 'password',
			'username' => 'admin'
		]);

		$user->roles()->sync([1, 2, 3]);

		$user = User::create([
			'email' => 'test@test.com',
			'password' => 'password',
			'username' => 'test'
		]);

		$user->roles()->sync([1, 2]);
	}
}
