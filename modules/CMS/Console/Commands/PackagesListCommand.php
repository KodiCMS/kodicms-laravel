<?php
namespace KodiCMS\CMS\Console\Commands;

use Package;
use Illuminate\Console\Command;
use Symfony\Component\Console\Helper\TableSeparator;

class PackagesListCommand extends Command
{

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
        'Package',
        'Files',
        'Dependency',
    ];


    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $packages = [];

        $i = 0;
        foreach (Package::getAll() as $id => $package) {
            foreach ($package as $file) {
                if (isset( $packages[$id] )) {
                    $packages[$i]['id']    = '';
                    $packages[$i]['files'] = $file['src'];
                    $packages[$i]['deps']  = $file['deps'];

                    $i++;
                } else {
                    $packages[$id]['id']    = $id;
                    $packages[$id]['files'] = $file['src'];
                    $packages[$id]['deps']  = $file['deps'];
                }
            }

            $packages[$i] = new TableSeparator;
            $i++;
        }

        foreach ($packages as $i => $data) {
            foreach ($data as $key => $rows) {
                if (is_array($rows)) {
                    $packages[$i][$key] = implode(', ', $rows);
                }
            }
        }

        $this->table($this->headers, $packages);
    }
}
