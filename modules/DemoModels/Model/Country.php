<?php namespace Modules\DemoModels\Model;

use Illuminate\Database\Eloquent\Model;
use KodiCMS\SleepingOwlAdmin\Traits\OrderableModel;

class Country extends Model
{
    use OrderableModel;

    protected $fillable = ['title', 'test'];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function contacts()
    {
        return $this->hasMany(Contact::class);
    }

    public function getOrderField()
    {
        return 'order';
    }

}
