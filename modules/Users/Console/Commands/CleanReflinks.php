<?php namespace KodiCMS\Users\Console\Commands;

use Illuminate\Console\Command;
use KodiCMS\Users\Model\UserReflink;

class CleanReflinks extends Command
{

	/**
	 * The console command name.
	 */
	protected $name = 'cms:reflinks:clean';

	/**
	 * Execute the console command.
	 */
	public function fire()
	{
		UserReflink::cleanOld();
		$this->info('All done');
	}
}
