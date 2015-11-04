<?php

namespace KodiCMS\Pages\Model;

use DB;
use Cache;
use KodiCMS\CMS\Model\File;

class Layout extends File
{
    /**
     * @var array
     */
    protected $blocks = null;

    /**
     * @return array
     */
    public function getBlocks()
    {
        return Cache::remember($this->getCacheKey(), 120, function () {
            return LayoutBlock::where('layout_name', $this->getName())
                ->lists('block')
                ->all();
        });
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
     *
     * @return bool
     * @throws Exception
     */
    public function save(array $data = [])
    {
        return parent::save($data);
    }

    /**
     * @return string
     */
    public function getViewFilename()
    {
        $filename = $this->getName();
        if (strpos($filename, '.blade') !== false) {
            $filename = str_replace('.blade', '', $this->getName());
        }

        return $filename;
    }

    /**
     * Обновление списка блоков шаблона.
     * @return mixed
     */
    public function findBlocks()
    {
        $blocks = LayoutBlock::findInString($this->getContent());

        DB::table('layout_blocks')->where('layout_name', $this->getName())->delete();

        $this->clearCache();

        foreach ($blocks as $position => $block) {
            LayoutBlock::create([
                'position'    => $position,
                'block'       => $block,
                'layout_name' => $this->getName(),
            ]);
        }

        return $blocks;
    }

    /**
     * @return string
     */
    protected function getCacheKey()
    {
        return "layout::blocks::{$this->getName()}";
    }

    protected function clearCache()
    {
        Cache::forget($this->getCacheKey());
    }
}
