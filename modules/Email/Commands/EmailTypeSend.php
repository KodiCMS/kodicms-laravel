<?php namespace KodiCMS\Email\Commands;

use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use KodiCMS\Email\Model\EmailType;

class EmailTypeSend implements SelfHandling
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
		$emailType = EmailType::whereCode($this->code)->first();
		if (is_null($emailType))
		{
			throw (new ModelNotFoundException)->setModel('KodiCMS\Email\Model\EmailType');
		}
		$emailType->send($this->options);
	}

}