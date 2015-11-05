<?php

namespace KodiCMS\Support\Helpers;

use Cache;
use Carbon\Carbon;
use ModulesFileSystem;

class File
{
    /**
     * @param string $path
     * @param string $ext
     *
     * @return string
     */
    public static function mergeByPath($path, $ext)
    {
        $cacheKey = 'files::merge::'.md5($path).'::'.$ext;

        $content = Cache::remember($cacheKey, Carbon::now()->minute(20), function () use ($path, $ext) {
            $return = '';
            $files = ModulesFileSystem::findFile('resources', $path, $ext, true);

            foreach ($files as $file) {
                if (config('app.debug')) {
                    $return .= "\n/**\n{$file}\n**/\n";
                }

                $return .= file_get_contents($file)."\n\n";
            }

            return $return;
        });

        return $content;
    }
}
