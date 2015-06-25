<?php namespace KodiCMS\Support\Helpers;

/**
 * File helper class.
 * TODO: убрать статику. Greabock 20.05.2015
 */
class File
{
	/**
	 * @param string $path
	 * @return string
	 */
	public static function normalizePath($path)
	{
		return str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $path);
	}

	/**
	 * Attempt to get the mime type from a file. This method is horribly
	 * unreliable, due to PHP being horribly unreliable when it comes to
	 * determining the mime type of a file.
	 *
	 *     $mime = File::mime($file);
	 *
	 * @param   string $filename file name or path
	 * @return  string  mime type on success
	 * @return  FALSE   on failure
	 */
	public static function mime($filename)
	{
		// Get the complete path to the file
		$filename = realpath($filename);

		// Get the extension from the filename
		$extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

		if (preg_match('/^(?:jpe?g|png|[gt]if|bmp|swf)$/', $extension)) {
			// Use getimagesize() to find the mime type on images
			$file = getimagesize($filename);

			if (isset($file['mime']))
				return $file['mime'];
		}

		if (class_exists('finfo', FALSE)) {
			if ($info = new finfo(defined('FILEINFO_MIME_TYPE') ? FILEINFO_MIME_TYPE : FILEINFO_MIME)) {
				return $info->file($filename);
			}
		}

		if (ini_get('mime_magic.magicfile') AND function_exists('mime_content_type')) {
			// The mime_content_type function is only useful with a magic file
			return mime_content_type($filename);
		}

		if (!empty($extension)) {
			return File::mimeByExt($extension);
		}

		// Unable to find the mime-type
		return FALSE;
	}

	/**
	 * Return the mime type of an extension.
	 *
	 *     $mime = File::mime_by_ext('png'); // "image/png"
	 *
	 * @param   string $extension php, pdf, txt, etc
	 * @return  string  mime type on success
	 * @return  FALSE   on failure
	 */
	public static function mimeByExt($extension)
	{
		// Load all of the mime types
		$mimes = config('mimes');

		return isset($mimes[$extension]) ? $mimes[$extension][0] : FALSE;
	}

	/**
	 * Lookup MIME types for a file
	 *
	 * @see Kohana_File::mime_by_ext()
	 * @param string $extension Extension to lookup
	 * @return array Array of MIMEs associated with the specified extension
	 */
	public static function mimesByExt($extension)
	{
		// Load all of the mime types
		$mimes = config('mimes');

		return isset($mimes[$extension]) ? ((array)$mimes[$extension]) : [];
	}

	/**
	 * Lookup file extensions by MIME type
	 *
	 * @param   string $type File MIME type
	 * @return  array   File extensions matching MIME type
	 */
	public static function extsByMime($type)
	{
		static $types = [];

		// Fill the static array
		if (empty($types)) {
			foreach (config('mimes') as $ext => $mimes) {
				foreach ($mimes as $mime) {
					if ($mime == 'application/octet-stream') {
						// octet-stream is a generic binary
						continue;
					}

					if (!isset($types[$mime])) {
						$types[$mime] = [(string)$ext];
					} elseif (!in_array($ext, $types[$mime])) {
						$types[$mime][] = (string)$ext;
					}
				}
			}
		}

		return isset($types[$type]) ? $types[$type] : FALSE;
	}

	/**
	 * Lookup a single file extension by MIME type.
	 *
	 * @param   string $type MIME type to lookup
	 * @return  mixed          First file extension matching or false
	 */
	public static function extByMime($type)
	{
		return current(File::extsByMime($type));
	}
}