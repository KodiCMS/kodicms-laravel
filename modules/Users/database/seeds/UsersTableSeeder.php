<?php namespace KodiCMS\Users\database\seeds;

use Illuminate\Database\Seeder;
use KodiCMS\Users\Model\User;
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
		\DB::table('users')->truncate();
		\DB::table('roles_users')->truncate();

		$roles = UserRole::get()->lists('id');
		$maxRolesToAtach = count($roles) > 4 ? 4 : count($roles);

		$faker = \Faker\Factory::create();
		$totalUsers = 50;

		$user = User::create([
			'email' => 'admin@site.com',
			'password' => 'password',
			'username' => 'admin'
		]);

		$user->roles()->sync([1, 2, 3]);

		$usedEmails = $usedUsernames = [];

		for ($i = 0; $i < $totalUsers; $i++) {
			do {
				$email = strtolower($faker->email);
			} while (in_array($email, $usedEmails));
			$usedEmails[] = $email;

			do {
				$username = strtolower($faker->userName);
			} while (in_array($username, $usedUsernames));
			$usedUsernames[] = $username;

			$user = User::create([
				'email' => $email,
				'password' => 'password',
				'username' => $username,
				'locale' => $faker->randomElement(['ru', 'en'])
			]);

			$user->roles()->attach($faker->randomElements($roles, rand(1, $maxRolesToAtach)));
		}
	}
}
