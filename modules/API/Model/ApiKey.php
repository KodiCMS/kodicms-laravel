<?php
namespace KodiCMS\API\Model;

use Keys;
use Illuminate\Database\Eloquent\Model;

class ApiKey extends Model
{

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
    protected $fillable = ['id', 'description'];


    /**
     * @param string $description
     *
     * @return integer|null
     */
    public function generate($description = '')
    {
        $key = static::create([
            'id'          => Keys::generate(),
            'description' => $description,
        ]);

        return $key->id;
    }


    /**
     * @param $oldKey
     *
     * @return bool|integer
     */
    public function refresh($oldKey)
    {
        $key = static::where('id', $oldKey)->first();

        if (is_null($key) or ! $key->exists) {
            return false;
        }

        $key->update([
            'id' => Keys::generate(),
        ]);

        return $key->id;
    }


    /**
     *
     * @param string $key
     *
     * @return bool
     */
    public function isValid($key)
    {
        return static::where('id', e($key))->first()->exists;
    }
}