<?php

namespace KodiCMS\Email\Http\Controllers\API;

use Mail;
use KodiCMS\Email\Support\EmailSender;
use KodiCMS\Email\Repository\EmailEventRepository;
use KodiCMS\API\Http\Controllers\System\Controller;
use KodiCMS\Email\Repository\EmailTemplateRepository;

class EmailEventController extends Controller
{
    /**
     * @param EmailEventRepository $repository
     */
    public function getOptions(EmailEventRepository $repository)
    {
        $uid = $this->getRequiredParameter('uid', 'required|numeric');

        $emailEvent = $repository->findOrFail($uid);
        $options = array_merge($emailEvent->fields, config('email.default_template_data'));

        $this->setContent($options);
    }

    /**
     * @param EmailTemplateRepository $repository
     */
    public function postSend(EmailTemplateRepository $repository)
    {
        $subject = $this->getRequiredParameter('subject');
        $to = $this->getRequiredParameter('to');
        $body = $this->getRequiredParameter('message');

        $parameters = $repository->instance([
            'subject'    => $subject,
            'email_to'   => $to,
            'email_from' => config('mail.default'),
        ]);

        $this->setContent(['send' => EmailSender::send($body, $parameters)]);
    }
}
