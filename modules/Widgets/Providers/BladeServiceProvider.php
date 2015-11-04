<?php

namespace KodiCMS\Widgets\Providers;

use Blade;
use KodiCMS\Support\ServiceProvider;

class BladeServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Blade::directive('widget', function ($expression) {
            return "<?php echo (new \\KodiCMS\\Widgets\\Engine\\WidgetRenderHTML{$expression})->render(); ?>";
        });

        Blade::directive('snippet', function ($expression) {
            return "<?php echo (new \\KodiCMS\\Widgets\\Model\\SnippetCollection)->findAndRender{$expression}; ?>";
        });
    }

    public function register()
    {
    }
}
