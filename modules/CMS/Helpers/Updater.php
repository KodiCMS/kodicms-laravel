<?php

namespace KodiCMS\CMS\Helpers;

use CMS;
use HTML;
use Cache;
use Carbon\Carbon;
use KodiCMS\Support\Helpers\Text;

class Updater
{
    const VERSION_NEW = 1;
    const VERSION_OLD = -1;
    const VERSION_CURRENT = 0;
    const CACHE_KEY = 'update::cache';
    const CACHE_KEY_VERSION = 'remote::version::cache';

    /**
     * @var string
     */
    protected $repository;

    /**
     * @var string
     */
    protected $branch;

    /**
     * @var string
     */
    protected $remoteVersion;

    /**
     * @var bool
     */
    protected $newsVersion = false;

    public function __construct()
    {
        $this->repository = config('cms.update.repository');
        $this->branch = config('cms.update.branch');
    }

    /**
     * Получение номер версии с удаленного сервера.
     * @return string
     */
    public function getRemoteVersion()
    {
        if (! $this->remoteVersion) {
            $this->checkVersion();
        }

        return $this->remoteVersion;
    }

    /**
     * @return bool
     */
    public function hasNewVersion()
    {
        if (! $this->remoteVersion) {
            $this->checkVersion();
        }

        return $this->newsVersion;
    }

    /**
     * Проверка номера версии в репозитории Github.
     * @return int
     */
    public function checkVersion()
    {
        $version = Cache::remember(static::CACHE_KEY_VERSION, Carbon::now()->addHours(24), function () {
            $response = self::request('https://raw.githubusercontent.com/:rep/:branch/modules/CMS/CMS.php');
            preg_match('/const VERSION[ ]?[\t]?\=[ ]?[\t]?[\'|"]([0-9a-z. ]+)\'\;/i', $response, $matches);

            return $matches[1];
        });

        $this->remoteVersion = $version;

        return $this->newsVersion = version_compare($this->remoteVersion, CMS::VERSION) == static::VERSION_NEW;
    }

    /**
     * Проверка файлов на различия, проверяется по размеру файла и наличие файла в ФС
     * @retun array
     */
    public function checkFiles()
    {
        $cachedFiles = Cache::remember(static::CACHE_KEY, Carbon::now()->addHours(6), function () {

            $response = $this->request('https://api.github.com/repos/:rep/git/trees/:branch?recursive=true');
            $response = json_decode($response, true);

            $files = ['new_files' => [], 'diff_files' => [], 'third_party_plugins' => []];

            if (isset($response['tree'])) {
                foreach ($response['tree'] as $row) {
                    $filePath = base_path($row['path']);

                    if (! file_exists($filePath)) {
                        $files['new_files'][] = $this->getFileUrlByPath($row['path']);
                        continue;
                    }

                    $fileSize = filesize($filePath);
                    if (isset($row['size']) and $fileSize != $row['size']) {
                        $diff = $fileSize - $this->countFileLines($filePath) - $row['size'];

                        if ($diff > 1 or $diff < -1) {
                            $files['diff_files'][] = [
                                'diff' => Text::bytes($diff),
                                'path' => $row['path'],
                                'url'  => $this->buildRemoteUrl('https://raw.githubusercontent.com/:rep/:branch/'.$row['path']),
                            ];
                        }
                    }
                }

                return $files;
            }
        });

        return $cachedFiles;
    }

    /**
     * @return string
     */
    public function newIssueUrl()
    {
        return $this->buildRemoteUrl('https://github.com/:rep/issues/new');
    }

    /**
     * @param $path
     *
     * @return string
     */
    public function getFileUrlByPath($path)
    {
        return $this->buildRemoteUrl('https://raw.githubusercontent.com/:rep/:branch/'.$path);
    }

    /**
     * Ссылка на удаленный репозиторий.
     *
     * @param string $name
     *
     * @return string
     */
    public function link($name)
    {
        return HTML::link($this->buildRemoteUrl('https://github.com/:rep/archive/:branch.zip'), $name);
    }

    /**
     * @param $path
     *
     * @return string
     */
    public function getRemoteFileContent($path)
    {
        return Cache::remember(md5($path), Carbon::now()->addMinutes(10), function () use ($path) {
            return $this->request($this->getFileUrlByPath($path));
        });
    }

    /**
     * @param string $url
     *
     * @return string
     */
    protected function buildRemoteUrl($url)
    {
        return strtr($url, [
            ':branch' => $this->branch,
            ':rep'    => $this->repository,
        ]);
    }

    /**
     * @param string $url
     *
     * @return string
     */
    protected function request($url)
    {
        $response = (new \GuzzleHttp\Client)->get($this->buildRemoteUrl($url), ['verify' => false]);

        return (string) $response->getBody();
    }

    /**
     * Подсчет кол-ва строк в файле.
     *
     * @param string $filePath
     *
     * @return int
     */
    protected function countFileLines($filePath)
    {
        $handle = fopen($filePath, 'r');
        $count = 0;
        while (fgets($handle)) {
            $count++;
        }
        fclose($handle);

        return $count;
    }
}
