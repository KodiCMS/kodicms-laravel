<?php namespace KodiCMS\Users\Console\Commands;

use Illuminate\Console\Command;

class DeleteExpiredReflinks extends Command
{

	/**
	 * The console command name.
	 */
	protected $name = 'cms:reflinks:delete_expired';

	/**
	 * Execute the console command.
	 */
	public function fire()
	{
		app('reflink.tokens')->deleteExpired();

		$this->info('All done');
	}
}
