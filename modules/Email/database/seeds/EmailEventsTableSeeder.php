<?php

namespace KodiCMS\Email\database\seeds;

use Illuminate\Database\Seeder;
use KodiCMS\Email\Model\EmailEvent;
use KodiCMS\Email\Model\EmailQueue;
use KodiCMS\Email\Repository\EmailEventRepository;

class EmailEventsTableSeeder extends Seeder
{
    /**
     * @var EmailEventRepository
     */
    protected $repository;

    public function __construct()
    {
        $this->repository = app(EmailEventRepository::class);
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        EmailEvent::truncate();
        EmailQueue::truncate();

        $this->repository->create([
            'code'   => 'user_request_password',
            'name'   => 'Запрос на восстановление пароля',
            'fields' => [
                'code'     => 'Код восстановления пароля',
                'username' => 'Имя пользователя',
                'email'    => 'Email пользователя',
                'reflink'  => 'Ссылка для восстановления пароля',
            ],
        ]);

        $this->repository->create([
            'code'   => 'user_new_password',
            'name'   => 'Новый пароль',
            'fields' => [
                'password' => 'Новый пароль',
                'email'    => 'Email пользователя',
                'username' => 'Имя пользователя',
            ],
        ]);
    }
}
