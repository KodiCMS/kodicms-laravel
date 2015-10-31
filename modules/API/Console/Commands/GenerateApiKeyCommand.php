<?php
namespace KodiCMS\API\Console\Commands;

use Illuminate\Console\Command;
use KodiCMS\Api\Repository\ApiKeyRepository;

class GenerateApiKeyCommand extends Command
{

    /**
     * The console command name.
     */
    protected $name = 'cms:api:generate-key';


    /**
     * Execute the console command.
     *
     * @param ApiKeyRepository $reposirory
     *
     * @return string
     */
    public function fire(ApiKeyRepository $reposirory)
    {
        $this->output->writeln('<info>Generating KodiCMS API key...</info>');

        $key = $reposirory->generate();

        if ( ! is_null($key)) {
            $this->output->writeln("<info>New API key generated: {$key}</info>");
        }

        return $key;
    }
}