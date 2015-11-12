<?php

namespace KodiCMS\Installer\Http\Controllers\API;

use Installer;
use Illuminate\Database\Connection;
use KodiCMS\API\Http\Controllers\System\Controller;

class InstallerController extends Controller
{
    /**
     * @var bool
     */
    protected $authRequired = false;

    public function postDatabaseCheck()
    {
        $post = (array) $this->getRequiredParameter('database');
        $this->setContent(
            Installer::createDBConnection(
                array_only($post, [
                    'driver',
                    'host',
                    'username',
                    'password',
                    'database',
                    'prefix',
                ])
            ) instanceof Connection
        );
    }
}
