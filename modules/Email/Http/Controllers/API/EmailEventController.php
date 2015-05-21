<?php namespace KodiCMS\Email\Http\Controllers\API;

use KodiCMS\API\Http\Controllers\System\Controller;
use KodiCMS\Email\Model\EmailType;
use KodiCMS\Email\Repository\EmailEventRepository;
use KodiCMS\Email\Support\EmailSender;
use Mail;

class EmailEventController extends Controller
{
	/**
	 * @var bool
	 */
	public $authRequired = true;

	public function getOptions(EmailEventRepository $repository)
	{
		$uid = $this->getRequiredParameter('uid', 'required|numeric');

		$emailEvent = $repository->findOrFail($uid);
		$options = array_merge($emailEvent->fields, config('email.default_template_data'));

		$this->setContent($options);
	}

	public function postSend()
	{
		$subject = $this->getRequiredParameter('subject');
		$to = $this->getRequiredParameter('to');
		$body = $this->getRequiredParameter('message');

		$parameters = new \stdClass;
		$parameters->subject = $subject;
		$parameters->email_to = $to;
		$parameters->email_from = config('mail.default');

		$sended = EmailSender::send($body, $parameters);

		$this->setContent([
			'send' => $sended,
		]);
	}

}