<?php namespace KodiCMS\Email\Model;

use Illuminate\Database\Eloquent\Model;

class EmailType extends Model
{

	protected $fillable = [
		'code',
		'name',
		'fields',
	];

	protected $casts = [
		'fields' => 'array',
	];

	public function templates()
	{
		return $this->hasMany('KodiCMS\Email\Model\EmailTemplate', 'email_type_id');
	}

	public function getFullNameAttribute()
	{
		return $this->name . ' (' . $this->code . ')';
	}

}