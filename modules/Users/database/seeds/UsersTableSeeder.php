<?php namespace KodiCMS\Users\database\seeds;

use KodiCMS\Users\Model\User;
use Illuminate\Database\Seeder;
use KodiCMS\Users\Model\UserRole;

class UsersTableSeeder extends Seeder
{

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		User::truncate();

		$roles = UserRole::get()->lists('id')->all();
		$user = User::create([
			'email' => 'admin@site.com',
			'password' => 'password',
			'username' => 'admin',
			'locale' => 'ru'
		]);

		$user->roles()->sync($roles);

		$user = User::create([
			'email' => 'admin_en@site.com',
			'password' => 'password',
			'username' => 'admin_en',
			'locale' => 'en'
		]);

		$user->roles()->sync($roles);
	}
}
