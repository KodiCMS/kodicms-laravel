<?php

namespace KodiCMS\Pages\Providers;

use Blade;
use KodiCMS\Support\ServiceProvider;

class BladeServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Blade::directive('meta', function ($expression) {
            return "<?php meta{$expression}; ?>";
        });

        Blade::directive('block', function ($expression) {
            return "<?php Block::run{$expression}; ?>";
        });

        Blade::directive('part', function ($expression) {
            return "<?php echo \\KodiCMS\\Pages\\PagePart::getContent{$expression}; ?>";
        });
    }

    public function register()
    {
    }
}
