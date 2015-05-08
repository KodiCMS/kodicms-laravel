<?php namespace KodiCMS\Email\database\seeds;

use Illuminate\Database\Seeder;
use KodiCMS\Email\Model\EmailType;

class EmailTypesTableSeeder extends Seeder
{

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		\DB::statement('SET FOREIGN_KEY_CHECKS=0');
		\DB::table('email_types')->truncate();
		\DB::statement('SET FOREIGN_KEY_CHECKS=1');

		EmailType::create([
			'code'   => 'user_request_password',
			'name'   => 'Запрос на восстановление пароля',
			'fields' => [
				'code'     => 'Код восстановления пароля',
				'username' => 'Имя пользователя',
				'email'    => 'Email пользователя',
				'reflink'  => 'Ссылка для восстановления пароля',
			]
		]);

		EmailType::create([
			'code'   => 'user_new_password',
			'name'   => 'Новый пароль',
			'fields' => [
				'password' => 'Новый пароль',
				'email'    => 'Email пользователя',
				'username' => 'Имя пользователя',
			]
		]);
	}
}
