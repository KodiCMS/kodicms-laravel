<?php namespace KodiCMS\Pages\Model;

use Illuminate\Database\Eloquent\Model;

class PagePart extends Model
{
	const PART_NOT_PROTECTED = 0;
	const PART_PROTECTED = 1;

	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'page_parts';

	public function page()
	{
		return $this->belongsTo('\KodiCMS\Pages\Model\Page', 'page_id');
	}

	/**
	 * TODO: сбрасывать кеширование частей страницы после сортировки
	 *
	 * @param array $positions
	 * @return $this
	 */
	public function reorder(array $positions)
	{
		foreach ($positions as $pos => $id)
		{
			\DB::table($this->table)
				->where('id', $id)
				->update([
					'position' => (int) $pos
				]);
		}

		return $this;
	}
}