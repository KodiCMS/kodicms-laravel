<?php namespace KodiCMS\Users\Console\Commands;

use Illuminate\Console\Command;

class deleteExpiredReflinks extends Command
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
		app('reflink.tokens')->deleteExpired();

		$this->info('All done');
	}
}
