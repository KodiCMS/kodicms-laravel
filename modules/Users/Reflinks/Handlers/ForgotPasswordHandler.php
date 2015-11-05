<?php

namespace KodiCMS\Users\Reflinks\Handlers;

use Bus;
use Auth;
use Password;
use KodiCMS\Email\Jobs\EmailSend;
use KodiCMS\Users\Model\UserReflink;
use KodiCMS\Users\Contracts\ReflinkHandlerInterface;

class ForgotPasswordHandler implements ReflinkHandlerInterface
{
    /**
     * @var UserReflink
     */
    protected $reflink;

    /**
     * @var string
     */
    protected $message;

    /**
     * @var string|null
     */
    protected $redirectUrl = null;

    /**
     * @param UserReflink $reflink
     */
    public function __construct(UserReflink $reflink)
    {
        $this->reflink = $reflink;
    }

    /**
     * @return string
     */
    public function getResponse()
    {
        return $this->message;
    }

    /**
     * @return string
     */
    public function getRedirectUrl()
    {
        return $this->redirectUrl;
    }

    public function handle()
    {
        $password = str_random(8);
        $user = $this->reflink->user;

        Bus::dispatch(new EmailSend('user_new_password', [
            'password' => $password,
            'username' => $user->username,
            'email'    => $user->email,
        ]));

        $user->password = $password;
        $user->save();

        Auth::login($user);

        $this->redirectUrl = backend_url();

        $this->message = trans(Password::PASSWORD_RESET);
    }
}
