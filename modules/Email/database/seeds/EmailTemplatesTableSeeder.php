<?php

namespace KodiCMS\Email\database\seeds;

use Illuminate\Database\Seeder;
use KodiCMS\Email\Model\EmailTemplate;
use KodiCMS\Email\Repository\EmailEventRepository;
use KodiCMS\Email\Repository\EmailTemplateRepository;

class EmailTemplatesTableSeeder extends Seeder
{
    /**
     * @var EmailTemplateRepository
     */
    protected $emailTemplateRepository;

    /**
     * @var EmailEventRepository
     */
    protected $emailEventRepository;

    public function __construct()
    {
        $this->emailTemplateRepository = app(EmailTemplateRepository::class);
        $this->emailEventRepository = app(EmailEventRepository::class);
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        EmailTemplate::truncate();

        $emailEvent = $this->emailEventRepository->query()->whereCode('user_request_password')->first();
        $this->emailTemplateRepository->create([
            'status'         => 1,
            'email_event_id' => $emailEvent->id,
            'email_from'     => '{default_email}',
            'email_to'       => '{email}',
            'subject'        => '{site_title}: Ссылка для восстановления пароля',
            'message'        => '<h3>Здраствуйте {username}!</h3>Чтобы восстановить &nbsp;пароль от своего аккаунта, пройдите, пожалуйста, по ссылке:  <a href="{reflink}">{reflink}</a>&nbsp;или введите код&nbsp;<b>{code}</b> вручную на странице восстановления.<p>----------------------------------------</p><p>Данное письмо сгенерировано автоматически, отвечать на него не нужно.<span style="line-height: 1.45em;"></span></p>',
            'message_type'   => 'html',
        ]);

        $emailEvent2 = $this->emailEventRepository->query()->whereCode('user_new_password')->first();
        $this->emailTemplateRepository->create([
            'status'         => 1,
            'email_event_id' => $emailEvent2->id,
            'email_from'     => '{email_from}',
            'email_to'       => '{email}',
            'subject'        => '{site_title}: Новый пароль от вашего аккаунта',
            'message'        => '<h3>Здраствуйте {username}!</h3>Ваш новый пароль:&nbsp;<b>{password}</b><p></p><p>Всегда храните свой пароль в тайне и&nbsp;не сообщайте его никому.<br></p><p>----------------------------------------</p><p><p>Данное письмо сгенерировано автоматически, отвечать на него не нужно.</p></p><p></p>',
            'message_type'   => 'html',
        ]);
    }
}
