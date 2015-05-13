<?php namespace KodiCMS\Email\Console\Commands;

use Illuminate\Console\Command;
use KodiCMS\Email\Model\EmailQueue;

class QueueClean extends Command
{

	/**
	 * The console command name.
	 */
	protected $name = 'cms:email:queue-clean';

	protected $description = 'Clean old queued emails';


	/**
	 * Execute the console command.
	 */
	public function fire()
	{
		EmailQueue::cleanOld();
		$this->info('All done');
	}

}
