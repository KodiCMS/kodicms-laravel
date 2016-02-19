<?php namespace Modules\DemoModels\Model;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{

    protected $table = 'news';

    protected $fillable = [
        'title',
        'date',
        'published',
        'text',
    ];

    public function scopeLast($query)
    {
        $query->orderBy('date', 'desc')->limit(4);
    }

}
