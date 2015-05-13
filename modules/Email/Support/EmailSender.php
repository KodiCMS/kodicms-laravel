<?php namespace KodiCMS\Email\Support;

use Mail;

class EmailSender
{

	public static function send($body, $parameters = null, $type = 'html')
	{
		if ($type == 'html')
		{
			$view = [
				'html' => 'email::email.messages.email'
			];
		} else
		{
			$view = [
				'text' => 'email::email.messages.email',
			];
		}

		try
		{
			Mail::send($view, ['body' => $body], function ($message) use ($parameters)
			{
				$message->from($parameters->email_from);
				$message->to($parameters->email_to);
				$message->subject($parameters->subject);
				if ( ! empty($parameters->cc))
				{
					$message->cc($parameters->cc);
				}
				if ( ! empty($parameters->bcc))
				{
					$message->bcc($parameters->bcc);
				}
				if ( ! empty($parameters->reply_to))
				{
					$message->replyTo($parameters->reply_to);
				}
			});
		} catch (\Exception $e)
		{
			return false;
		}
		return true;
	}

} 