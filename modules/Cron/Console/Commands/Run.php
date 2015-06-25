<?php namespace KodiCMS\Cron\Console\Commands;

use Illuminate\Console\Command;
use KodiCMS\Cron\Model\Job;

class Run extends Command
{

	/**
	 * The console command name.
	 */
	protected $name = 'cms:cron:run';

	/**
	 * @var string
	 */
	protected $description = 'Run all cron tasks';

	/**
	 * Execute the console command.
	 */
	public function fire()
	{
		Job::runAll();
		$this->info('All done');
	}

}
