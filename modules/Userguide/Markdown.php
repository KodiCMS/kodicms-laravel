<?php namespace KodiCMS\Userguide;

class Markdown extends \Michelf\MarkdownExtra
{
	/**
	 * @var  string  base url for links
	 */
	public static $baseUrl = '';

	public function __construct()
	{
		// doLink is 20, add base url just before
		$this->span_gamut['doBaseURL'] = 19;

		// Add note spans last
		$this->span_gamut['doNotes'] = 100;

		parent::__construct();
	}

	/**
	 * Add the current base url to all local links.
	 *
	 *     [filesystem](about.filesystem "Optional title")
	 *
	 * @param   string  span text
	 *
	 * @return  string
	 */
	public function doBaseURL($text)
	{
		// URLs containing "://" are left untouched
		return preg_replace('~(?<!!)(\[.+?\]\()(\/docs\/\{\{version\}\}\/){0,1}(?!\w++://)(?!#)(\S*(?:\s*+".+?")?\))~', '$1' . static::$baseUrl . '/$3', $text);
	}

	/**
	 * Wrap notes in the applicable markup. Notes can contain single newlines.
	 *
	 *     [!!] Remember the milk!
	 *
	 * @param   string  span text
	 * @return  string
	 */
	public function doNotes($text)
	{
		if ( ! preg_match('/^\[!!\]\s*+(.+?)(?=\n{2,}|$)/s', $text, $match))
		{
			return $text;
		}

		return $this->hashBlock('<p class="alert alert-warning alert-dark">'.$match[1].'</p>');
	}
}