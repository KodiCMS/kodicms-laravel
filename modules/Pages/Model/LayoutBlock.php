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
		preg_match_all("/Block::([a-z_]{3,5})\(\'([0-9a-zA-Z\_\-\.]+)\'(\,.*)?\)/i", $content, $blocks);

		if (!empty($blocks[2]))
		{
			return $blocks[2];
		}

		return [];
	}
}