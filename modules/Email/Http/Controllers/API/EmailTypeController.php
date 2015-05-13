<?php namespace KodiCMS\Email\Http\Controllers\API;

use KodiCMS\API\Http\Controllers\System\Controller;
use KodiCMS\Email\Model\EmailType;
use Mail;

class EmailTypeController extends Controller
{
	/**
	 * @var bool
	 */
	public $authRequired = true;

	public function getOptions()
	{
		$uid = $this->getRequiredParameter('uid', 'required|numeric');

		$emailType = EmailType::findOrFail($uid);
		$options = array_merge($emailType->fields, config('email.default_template_data'));

		$this->setContent($options);
	}

	public function postSend()
	{
		$subject = $this->getRequiredParameter('subject');
		$to = $this->getRequiredParameter('to');
		$body = $this->getRequiredParameter('message');

		try
		{
			$sended = Mail::send('email::email.messages.email', compact('body'), function ($message) use ($subject, $to)
			{
				$message->to($to)->subject($subject);
			});
		} catch (\Exception $e)
		{
			$sended = false;
		}
		$this->setContent([
			'send' => $sended,
		]);
	}

}