<?php namespace KodiCMS\Pages\Model;

use DB;
use KodiCMS\CMS\Model\File;

class Layout extends File
{

	/**
	 *
	 * @var array
	 */
	protected $blocks = NULL;

	/**
	 *
	 * @return array
	 */
	public function getBlocks()
	{
		return LayoutBlock::where('layout_name', $this->getName())->lists('block')->all();
	}

	/**
	 * @return bool
	 */
	public function isUsed()
	{
		return (bool) DB::table('pages')
			->where('layout_file', $this->getName())
			->count();
	}

	/**
	 * @param array $data
	 * @return bool
	 * @throws Exception
	 */
	public function save(array $data = [])
	{
		return parent::save($data);
	}

	public function getViewFilename()
	{
		$filename = $this->getName();
		if(strpos($filename, '.blade') !== FALSE)
		{
			$filename = str_replace('.blade', '', $this->getName());
		}

		return $filename;
	}

	/**
	 * Обновление списка блоков шаблона
	 * TODO: добавить кеширование
	 * @return mixed
	 */
	public function findBlocks()
	{
		$blocks = LayoutBlock::findInString($this->getContent());

		DB::table('layout_blocks')
			->where('layout_name', $this->getName())
			->delete();

		$insertData = [];
		foreach ($blocks as $position => $block) {
			$insertData[] = [
				'position' => $position,
				'block' => $block,
				'layout_name' => $this->getName()
			];
		}

		if (count($insertData) > 0)
		{
			DB::table('layout_blocks')->insert($insertData);
		}

		return $blocks;
	}
}