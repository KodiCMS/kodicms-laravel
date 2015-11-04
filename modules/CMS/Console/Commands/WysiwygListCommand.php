<?php

namespace KodiCMS\CMS\Console\Commands;

use WYSIWYG;
use Illuminate\Console\Command;
use Symfony\Component\Console\Helper\TableSeparator;

class WysiwygListCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'cms:wysiwyg:list';

    /**
     * The table headers for the command.
     *
     * @var array
     */
    protected $headers = [
        'Name',
        'Settings',
    ];

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $editors = [];

        foreach (WYSIWYG::getAvailable() as $editor) {
            $editors[] = [
                $editor->getName().' [type: '.$editor->getType().']',
                '',
            ];
            $editors[] = new TableSeparator;
            foreach ($editor->toArray() as $key => $value) {
                $editors[] = [
                    studly_case($key),
                    $value,
                ];
            }

            $editors[] = new TableSeparator;
        }

        $this->table($this->headers, $editors);
    }
}
