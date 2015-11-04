<?php

namespace KodiCMS\Pages\Model;

use DB;
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
    public $incrementing = false;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Метод служит для поиска в переданном шаблоне размеченных блоков.
     *
     * @param string $content
     *
     * @return string
     */
    public static function findInString($content)
    {
        $content = str_replace(' ', '', $content);

        return array_unique(
            array_merge(
                static::findInBladeTemplate($content), static::findInPHPTemplate($content)
            )
        );
    }

    /**
     * @param string $content
     *
     * @return array
     */
    protected static function findInBladeTemplate($content)
    {
        preg_match_all("/@block\(\'([0-9a-zA-Z\_\-\.]+)\'(\,.*)?\)/", $content, $blocks);

        return is_array($blocks[1]) ? $blocks[1] : [];
    }

    /**
     * @param string $content
     *
     * @return array
     */
    protected static function findInPHPTemplate($content)
    {
        preg_match_all("/Block::(run|def)\(\'([0-9a-zA-Z\_\-\.]+)\'(\,.*)?\)/i", $content, $blocks);

        return is_array($blocks[2]) ? $blocks[2] : [];
    }

    /**
     * Получение списка блоков по умолчанию.
     * @return array
     */
    public static function getDefaultBlocks()
    {
        return [
            -1     => trans('widgets::core.label.remove_from_page'),
            0      => trans('widgets::core.label.hide'),
            'PRE'  => trans('widgets::core.label.before_page_render'),
            'POST' => trans('widgets::core.label.after_page_render'),
        ];
    }

    /**
     * @param null|string $layoutName
     *
     * @return array
     */
    public function getBlocksGroupedByLayouts($layoutName = null)
    {
        $data = [];

        $query = DB::table($this->getTable());

        if (! is_null($layoutName)) {
            $query->where('layout_name', $layoutName);
        }

        foreach ($query->get() as $row) {
            if (empty($data[$row->layout_name])) {
                $data[$row->layout_name] = static::getDefaultBlocks();
            }

            $data[$row->layout_name][$row->block] = $row->block;
        }

        foreach ($data as $layoutName => $blocks) {
            $post = $blocks['POST'];
            unset($data[$layoutName]['POST']);
            $data[$layoutName]['POST'] = $post;
        }

        foreach ((new LayoutCollection)->getFiles() as $file) {
            if (! isset($data[$file->getName()])) {
                $data[$file->getName()] = static::getDefaultBlocks();
            }
        }

        return $data;
    }
}
