<?php
namespace KodiCMS\Users\Console\Commands;

use Illuminate\Console\Command;

class DeleteExpiredReflinksCommand extends Command
{

    /**
     * The console command name.
     */
    protected $name = 'cms:reflinks:delete-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete expired referial links';


    /**
     * Execute the console command.
     */
    public function fire()
    {
        app('reflink.tokens')->deleteExpired();
        $this->info('All done');
    }
}
