<?php namespace KodiCMS\Pages\Model;

use Illuminate\Database\Eloquent\Model;

class LayoutBlock extends Model
{
	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'layout_blocks';

	/**
	 * Indicates if the IDs are auto-incrementing.
	 *
	 * @var bool
	 */
	public $incrementing = FALSE;


	/**
	 * Indicates if the model should be timestamped.
	 *
	 * @var bool
	 */
	public $timestamps = FALSE;

	/**
	 * The attributes that aren't mass assignable.
	 *
	 * @var array
	 */
	protected $guarded = [];

	/**
	 * Метод служит для поиска в переданном шаблоне размеченных блоков
	 *
	 * @param string $content
	 * @return string
	 */
	public static function findInString($content)
	{
		$content = str_replace(' ', '', $content);
		return array_unique(array_merge(static::findInBladeTemplate($content), static::findInPHPTemplate($content)));
	}

	/**
	 * @param string $content
	 * @return array
	 */
	protected static function findInBladeTemplate($content)
	{
		preg_match_all("/block_(run|def)\(\'([0-9a-zA-Z\_\-\.]+)\'(\,.*)?\)/i", $content, $blocks);
		return is_array($blocks[2]) ? $blocks[2] : [];
	}

	/**
	 * @param string $content
	 * @return array
	 */
	protected static function findInPHPTemplate($content)
	{
		preg_match_all("/Block::(run|def)\(\'([0-9a-zA-Z\_\-\.]+)\'(\,.*)?\)/i", $content, $blocks);
		return is_array($blocks[2]) ? $blocks[2] : [];
	}
}