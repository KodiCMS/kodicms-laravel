<?php

namespace KodiCMS\Plugins\Model;

use Illuminate\Database\Eloquent\Model;

class Plugin extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'installed_plugins';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'path', 'settings'];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id'       => 'integer',
        'name'     => 'string',
        'path'     => 'string',
        'settings' => 'array',
    ];
}
