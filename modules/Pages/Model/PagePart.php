<?php

namespace KodiCMS\Pages\Model;

use DB;
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

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id', 'created_at', 'updated_at', 'is_developer', 'is_protected', 'content_html'];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'is_developer' => 'boolean',
        'is_protected' => 'boolean',
        'is_expanded'  => 'boolean',
        'is_indexable' => 'boolean',
        'position'     => 'integer',
        'page_id'      => 'integer',
        'name'         => 'string',
    ];

    public function page()
    {
        return $this->belongsTo(Page::class, 'page_id');
    }

    /**
     * TODO: сбрасывать кеширование частей страницы после сортировки.
     *
     * @param array $positions
     *
     * @return $this
     */
    public function reorder(array $positions)
    {
        foreach ($positions as $pos => $id) {
            DB::table($this->table)->where('id', $id)->update(['position' => (int) $pos]);
        }

        return $this;
    }
}
