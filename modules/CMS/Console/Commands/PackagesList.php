<?php namespace KodiCMS\CMS\Console\Commands;

use Illuminate\Console\Command;
use KodiCMS\CMS\Assets\Package;
use Symfony\Component\Console\Helper\TableSeparator;

class PackagesList extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'cms:packages:list';

	/**
	 * The table headers for the command.
	 *
	 * @var array
	 */
	protected $headers = [
		'Package', 'Scripts', 'Styles', 'Dependency'
	];

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		$packages = [];

		foreach(Package::getAll() as $id => $package)
		{
			$row = [
				'id' => $id,
				'js' => [],
				'css' => [],
				'deps' => []
			];

			foreach($package as $file)
			{
				$row[$file['type']][] = $file['src'];
				$row['deps'] += $file['deps'];
			}

			$packages[$id] = $row;
			$packages[] = new TableSeparator;
		}

		foreach($packages as $id => $data)
		{
			foreach($data as $key => $rows)
			{
				if (is_array($rows))
				{
					$packages[$id][$key] = '[' . implode('], [', $rows) . ']';
				}
			}
		}

		$this->table($this->headers, $packages);
	}
}
