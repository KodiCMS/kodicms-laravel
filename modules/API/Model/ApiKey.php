<?php

namespace KodiCMS\API\Model;

use Keys;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ApiKey.
 *
 * @property string $id
 * @property string $description
 *
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class ApiKey extends Model
{
    protected static function boot()
    {
        parent::boot();

        static::creating(function (ApiKey $key) {
            $key->id = Keys::generate();
        });
    }

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'api_keys';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['description'];

    /**
     * @param string $description
     *
     * @return int|null
     */
    public function generate($description = '')
    {
        $key = static::create([
            'description' => $description,
        ]);

        return $key->id;
    }

    /**
     * @param $oldKey
     *
     * @return bool|int
     */
    public function refresh($oldKey)
    {
        if (is_null($key = static::where('id', $oldKey)->first())) {
            return false;
        }

        $key->id = Keys::generate();
        $key->save();

        return $key->id;
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public function isValid($key)
    {
        return ! is_null(static::where('id', e($key))->first());
    }
}
