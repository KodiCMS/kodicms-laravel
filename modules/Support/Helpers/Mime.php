<?php

namespace KodiCMS\Support\Helpers;

class Mime
{
    /**
     * @return array
     */
    public static function getList()
    {
        return config('mimes', []);
    }

    /**
     * Attempt to get the mime type from a file. This method is horribly
     * unreliable, due to PHP being horribly unreliable when it comes to
     * determining the mime type of a file.
     *
     *     $mime = File::mime($file);
     *
     * @param   string $filename file name or path
     *
     * @return  string  mime type on success
     * @return  false   on failure
     */
    public static function byFilename($filename)
    {
        // Get the complete path to the file
        $filename = realpath($filename);

        // Get the extension from the filename
        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        if (preg_match('/^(?:jpe?g|png|[gt]if|bmp|swf)$/', $extension)) {
            // Use getimagesize() to find the mime type on images
            $file = getimagesize($filename);

            if (isset($file['mime'])) {
                return $file['mime'];
            }
        }

        if (ini_get('mime_magic.magicfile') and function_exists('mime_content_type')) {
            // The mime_content_type function is only useful with a magic file
            return mime_content_type($filename);
        }

        if (! empty($extension)) {
            return static::byExt($extension);
        }

        // Unable to find the mime-type
        return false;
    }

    /**
     * Return the mime type of an extension.
     *
     *     $mime = Mime::byExt('png'); // "image/png"
     *
     * @param   string $extension php, pdf, txt, etc
     *
     * @return  string  mime type on success
     * @return  false   on failure
     */
    public static function byExt($extension)
    {
        // Load all of the mime types
        $mimes = static::getList();

        return isset($mimes[$extension]) ? $mimes[$extension][0] : false;
    }

    /**
     * Lookup a single file extension by MIME type.
     *
     * @param   string $type MIME type to lookup
     *
     * @return  mixed First file extension matching or false
     */
    public static function extByMime($type)
    {
        return current(static::extsByMime($type));
    }

    /**
     * Lookup file extensions by MIME type.
     *
     * @param   string $type File MIME type
     *
     * @return  array File extensions matching MIME type
     */
    public static function extsByMime($type)
    {
        static $types = [];

        // Fill the static array
        if (empty($types)) {
            foreach (static::getList() as $ext => $mimes) {
                foreach ($mimes as $mime) {
                    if ($mime == 'application/octet-stream') {
                        // octet-stream is a generic binary
                        continue;
                    }

                    if (! isset($types[$mime])) {
                        $types[$mime] = [(string) $ext];
                    } elseif (! in_array($ext, $types[$mime])) {
                        $types[$mime][] = (string) $ext;
                    }
                }
            }
        }

        return isset($types[$type]) ? $types[$type] : false;
    }
}
