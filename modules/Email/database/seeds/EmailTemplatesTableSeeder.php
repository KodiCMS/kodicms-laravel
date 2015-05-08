<?php namespace KodiCMS\Email\database\seeds;

use Illuminate\Database\Seeder;
use KodiCMS\Email\Model\EmailTemplate;
use KodiCMS\Email\Model\EmailType;

class EmailTemplatesTableSeeder extends Seeder
{

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		\DB::statement('SET FOREIGN_KEY_CHECKS=0');
		\DB::table('email_templates')->truncate();
		\DB::statement('SET FOREIGN_KEY_CHECKS=1');
		
		$emailType = EmailType::whereCode('user_request_password')->first();
		EmailTemplate::create([
			'status' => 1,
			'email_type_id' => $emailType->id,
			'email_from' => '{default_email}',
			'email_to' => '{email}',
			'subject' => '{site_title}: Ссылка для восстановления пароля',
			'message' => '<h3>Здраствуйте {username}!</h3>Чтобы восстановить &nbsp;пароль от своего аккаунта, пройдите, пожалуйста, по ссылке:  <a href="{base_url}{reflink}">{base_url}{reflink}</a>&nbsp;или введите код&nbsp;<b>{code}</b> вручную на странице восстановления.<p>----------------------------------------</p><p>Данное письмо сгенерировано автоматически, отвечать на него не нужно.<span style="line-height: 1.45em;"></span></p>',
			'message_type' => 'html',
		]);

		$emailType2 = EmailType::whereCode('user_new_password')->first();
		EmailTemplate::create([
			'status' => 1,
			'email_type_id' => $emailType2->id,
			'email_from' => '{email_from}',
			'email_to' => '{email}',
			'subject' => '{site_title}: Новый пароль от вашего аккаунта',
			'message' => '<h3>Здраствуйте {username}!</h3>Ваш новый пароль:&nbsp;<b>{password}</b><p></p><p>Всегда храните свой пароль в тайне и&nbsp;не сообщайте его никому.<br></p><p>----------------------------------------</p><p><p>Данное письмо сгенерировано автоматически, отвечать на него не нужно.</p></p><p></p>',
			'message_type' => 'html',
		]);
	}
}
