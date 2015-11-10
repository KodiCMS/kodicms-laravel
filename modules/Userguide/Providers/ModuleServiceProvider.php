<?php

namespace KodiCMS\Userguide\Providers;

use Event;
use KodiCMS\Navigation\Page;
use KodiCMS\Navigation\Navigation;
use KodiCMS\Support\ServiceProvider;

class ModuleServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Event::listen('navigation.inited', function (Navigation $navigation) {
            $modules = array_reverse(config('userguide.modules'));

            // Remove modules that have been disabled via config
            foreach ($modules as $key => $value) {
                if (! config('userguide.modules.'.$key.'.enabled')) {
                    continue;
                }

                if (! is_null($section = $navigation->findSectionOrCreate('Documentation'))) {
                    $section->addPage(new Page([
                        'name'  => $key,
                        'icon'  => 'leanpub',
                        'label' => config('userguide.modules.'.$key.'.name'),
                        'url'   => route('backend.userguide.docs', [$key]),
                    ]));
                }
            }
        });
    }

    public function register()
    {
    }
}
