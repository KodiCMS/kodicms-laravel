<?php namespace KodiCMS\Datasource\Model;

use KodiCMS\Datasource\Exceptions\FieldException;
use KodiCMS\Support\Facades\FieldManager;
use Illuminate\Database\Eloquent\Model;
use KodiCMS\Datasource\Contracts\FieldInterface;

class Field extends Model implements FieldInterface
{
	/**
	 * @var array
	 */
	protected static $cachedFields = [];

	/**
	 * @var string
	 */
	protected $table = 'datasource_fields';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'ds_id', 'key', 'type', 'name', 'related_ds',
		'is_system', 'position', 'settings'
	];

	/**
	 * The attributes that should be casted to native types.
	 *
	 * @var array
	 */
	protected $casts = [
		'ds_id' => 'integer',
		'key' => 'string',
		'type' => 'string',
		'name' => 'string',
		'related_ds' => 'integer',
		'is_system' => 'boolean',
		'position' => 'integer',
		'settings' => 'array'
	];

	/**
	 * @var \KodiCMS\Datasource\Fields\Field|null
	 */
	protected $field = null;

	/**
	 * @return FieldInterface
	 * @throws FieldException
	 */
	public function toField()
	{
		if (!is_null($this->field))
		{
			return $this->field;
		}

		if (array_key_exists($this->id, static::$cachedFields))
		{
			return $this->field = static::$cachedFields[$this->id];
		}

		$class = FieldManager::getClassNameByType($this->type);

		if (is_null($class))
		{
			throw new FieldException("Field type [{$this->type}] not found");
		}

		if (!class_exists($class))
		{
			throw new FieldException("Field [{$class}] not found");
		}

		return static::$cachedFields[$this->id] = $this->field = new $class($this);
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function section()
	{
		return $this->belongsTo('KodiCMS\Datasource\Model\Section', null, 'ds_id');
	}
}