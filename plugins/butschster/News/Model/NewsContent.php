<?php
namespace Plugins\butschster\News\Model;

use Illuminate\Database\Eloquent\Model;
use KodiCMS\Support\Model\ModelFieldTrait;
use Plugins\butschster\News\Model\FieldCollections\NewsContentFieldCollection;

class NewsContent extends Model
{

    use ModelFieldTrait;

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'news_id';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'news_content';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['description', 'description_filtered', 'content', 'content_filtered'];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'news_id' => 'integer',
    ];


    /**
     * @return array
     */
    protected function fieldCollection()
    {
        return new NewsContentFieldCollection;
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function article()
    {
        return $this->belongsTo(News::class, 'news_id');
    }
}