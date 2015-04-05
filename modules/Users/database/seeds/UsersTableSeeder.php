<?php namespace KodiCMS\Users\Database\Seeds;

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

		$user->roles()->sync([1, 2]);
	}
}
