<?php namespace KodiCMS\Pages\Console\Commands;

use Illuminate\Console\Command;
use KodiCMS\Pages\Model\LayoutCollection;

class RebuldLayoutBlocks extends Command
{

	/**
	 * The console command name.
	 */
	protected $name = 'layout:rebuild_blocks';


	/**
	 * Execute the console command.
	 */
	public function fire()
	{
		$this->output->writeln('<info>Rebuilding layout blocks...</info>');

		$layouts = new LayoutCollection;

		$blocks = [];

		foreach($layouts as $layout)
		{
			$blocks = $layout->findBlocks();

			$blocks = !empty($blocks) ? implode(', ', $blocks) : 'null';
			$this->output->writeln("<info>Found blocks for layout [{$layout->getName()}]: {$blocks}</info>");
		}
	}
}
