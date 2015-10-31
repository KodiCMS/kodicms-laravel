<?php
namespace KodiCMS\Support\Loader;

use App;
use Illuminate\Routing\Router;
use KodiCMS\ModulesLoader\ModuleContainer as BaseModuleContainer;

class ModuleContainer extends BaseModuleContainer
{

    /**
     * @var string
     */
    protected $namespace = 'KodiCMS';


    /**
     * @param \Illuminate\Foundation\Application $app
     *
     * @return $this
     */
    public function boot($app)
    {
        if ( ! $this->isBooted) {
            $this->loadAssets();
        }

        return parent::boot($app);
    }


    /**
     * @return string
     */
    public function getAssetsPackagesPath()
    {
        return $this->getPath(['resources', 'packages.php']);
    }


    /**
     * @param Router $router
     */
    public function loadRoutes(Router $router)
    {
        if ( ! App::installed()) {
            return;
        }

        parent::loadRoutes($router);
    }


    /**
     * Register a config file namespace.
     * @return void
     */
    public function loadConfig()
    {
        if ( ! App::installed()) {
            return [];
        }

        return parent::loadConfig();
    }


    /**
     * Include assets package file
     *
     * @return void
     */
    protected function loadAssets()
    {
        if (is_file($packagesFile = $this->getAssetsPackagesPath())) {
            require $packagesFile;
        }
    }
}