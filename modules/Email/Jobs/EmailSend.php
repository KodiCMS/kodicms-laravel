<?php namespace KodiCMS\Email\Jobs;

use KodiCMS\Email\Model\EmailEvent;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class EmailSend implements SelfHandling
{
	/**
	 * @var string
	 */
	protected $code;

	/**
	 * @var array
	 */
	protected $options = [];

	/**
	 * @param string $code
	 * @param array $options
	 */
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
			throw (new ModelNotFoundException)->setModel(EmailEvent::class);
		}

		$emailEvent->send($this->options);
	}

}