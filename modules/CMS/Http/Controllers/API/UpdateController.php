<?php

namespace KodiCMS\CMS\Http\Controllers\API;

use KodiCMS\CMS\Helpers\Updater;
use KodiCMS\API\Exceptions\Exception;
use KodiCMS\API\Http\Controllers\System\Controller;
use KodiCMS\Notifications\Types\CMSNewVersionNotification;

class UpdateController extends Controller
{
    /**
     * @param Updater $updater
     */
    public function checkNewVersion(Updater $updater)
    {
        $this->setContent(
            (new CMSNewVersionNotification($updater))->toArray()
        );
    }

    /**
     * @param Updater $updater
     */
    public function checkRemoteFiles(Updater $updater)
    {
        $files = $updater->checkFiles();
        $this->setContent(
            view('cms::system.remote_files_check', compact('files'))
        );
    }

    /**
     * @param Updater $updater
     */
    public function diffFiles(Updater $updater)
    {
        $path = $this->getRequiredParameter('path');

        $localFile = base_path($path);
        if (! is_file($localFile)) {
            throw new Exception("File [{$path}] not found");
        }

        $remoteFileContent = $updater->getRemoteFileContent($path);
        $localFileContent = file_get_contents($localFile);

        $this->setContent(
            view('cms::system.diff_files', compact('remoteFileContent', 'localFileContent'))
        );
    }
}
