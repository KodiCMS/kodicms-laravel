<?php namespace KodiCMS\Email\Commands;

use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use KodiCMS\Email\Model\EmailEvent;

class EmailSend implements SelfHandling
{

	protected $code;
	protected $options;

	function __construct($code, $options = [])
	{
		$this->code = $code;
		$this->options = $options;
	}

	public function handle()
	{
		$emailEvent = EmailEvent::whereCode($this->code)->first();
		if (is_null($emailEvent))
		{
			throw (new ModelNotFoundException)->setModel('KodiCMS\Email\Model\EmailEvent');
		}
		$emailEvent->send($this->options);
	}

}