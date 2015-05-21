<?php namespace KodiCMS\Email\Commands;

use Illuminate\Contracts\Bus\SelfHandling;
use KodiCMS\Email\Model\EmailType;

class EmailTypeCreate implements SelfHandling
{

	protected $code;
	protected $name;
	protected $fields;

	function __construct($code, $name, $fields = [])
	{
		$this->code = $code;
		$this->name = $name;
		$this->fields = $fields;
	}

	public function handle()
	{
		EmailType::create([
			'code'   => $this->code,
			'name'   => $this->name,
			'fields' => $this->fields,
		]);
	}

}