<?php namespace KodiCMS\Datasource\Model;

use DatasourceManager;
use Illuminate\Database\Eloquent\Model;
use KodiCMS\Datasource\Exceptions\SectionException;
use KodiCMS\Datasource\Contracts\SectionInterface;

class Section extends Model implements SectionInterface
{
	// TODO: вынести в отдельный Observer
	protected static function boot()
	{
		parent::boot();

		static::creating(function($section)
		{
			$section->settings = $section->toSection()->getSettings();
		});

		static::created(function($section)
		{
			$section = $section->toSection();
			DatasourceManager::createTableSection($section);
		});
	}

	/**
	 * @var array
	 */
	protected static $cachedSections = [];

	/**
	 * @var string
	 */
	protected $table = 'datasources';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'folder_id', 'type', 'name', 'description',
		'is_indexable', 'created_by_id', 'settings'
	];

	/**
	 * The attributes that should be casted to native types.
	 *
	 * @var array
	 */
	protected $casts = [
		'folder_id' => 'integer',
		'type' => 'string',
		'name' => 'string',
		'description' => 'string',
		'is_indexable' => 'boolean',
		'created_by_id' => 'integer',
		'settings' => 'array'
	];

	/**
	 * @var \KodiCMS\Datasource\Sections\Section
	 */
	protected $section = null;

	/**
	 * @return SectionInterface
	 * @throws SectionException
	 */
	public function toSection()
	{
		if (!is_null($this->section))
		{
			return $this->section;
		}

		if (array_key_exists($this->id, static::$cachedSections))
		{
			return $this->section = static::$cachedSections[$this->id];
		}

		$class = DatasourceManager::getClassNameByType($this->type);

		if (is_null($class))
		{
			throw new SectionException("Section type [{$this->type}] not found");
		}

		if (!class_exists($class))
		{
			throw new SectionException("Section [{$class}] not found");
		}

		return static::$cachedSections[$this->id] = $this->section =  new $class($this);
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function fields()
	{
		return $this->hasMany('KodiCMS\Datasource\Model\Field', 'ds_id');
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function folder()
	{
		return $this->belongsTo('KodiCMS\Datasource\Model\SectionFolder', 'folder_id');
	}
}