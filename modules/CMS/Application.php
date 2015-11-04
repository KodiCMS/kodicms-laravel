<?php

namespace KodiCMS\CMS;

use KodiCMS\Support\Helpers\Profiler;
use Illuminate\Support\ServiceProvider;

class Application extends \Illuminate\Foundation\Application
{
    /**
     * Register a service provider with the application.
     *
     * @param  \Illuminate\Support\ServiceProvider|string $provider
     * @param  array                                      $options
     * @param  bool                                       $force
     *
     * @return \Illuminate\Support\ServiceProvider
     */
    public function register($provider, $options = [], $force = false)
    {
        $className = is_object($provider) ? get_class($provider) : class_basename($provider);
        $token = Profiler::start('Providers', "$className::register");
        $provider = parent::register($provider, $options, $force);
        Profiler::stop($token);

        return $provider;
    }

    /**
     * Boot the given service provider.
     *
     * @param  \Illuminate\Support\ServiceProvider $provider
     *
     * @return void
     */
    protected function bootProvider(ServiceProvider $provider)
    {
        if (method_exists($provider, 'boot')) {
            $className = is_object($provider) ? get_class($provider) : class_basename($provider);
            $token = Profiler::start('Providers', "$className::boot");
            $return = parent::bootProvider($provider);
            Profiler::stop($token);

            return $return;
        }
    }
}
