<?php

namespace KodiCMS\Datasource\Model;

use Illuminate\Database\Eloquent\Model;
use KodiCMS\Datasource\Contracts\FolderInterface;

class SectionFolder extends Model implements FolderInterface
{
    protected $table = 'datasource_folders';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'position',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'name'     => 'string',
        'position' => 'integer',
    ];
}
