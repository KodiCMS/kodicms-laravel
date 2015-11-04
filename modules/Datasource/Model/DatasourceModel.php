<?php

namespace KodiCMS\Datasource\Model;

use Illuminate\Database\Eloquent\Model;
use KodiCMS\CMS\Exceptions\Exception;
use KodiCMS\Support\Traits\ModelSettings;

class DatasourceModel extends Model
{
    use ModelSettings;

    /**
     * @var bool
     */
    protected $initialized = false;

    /**
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        if (empty($attributes['type'])) {
            $attributes['type'] = $this->getManagerClass()->getTypeByClassName(get_called_class());
        }

        parent::__construct($attributes);

        if (method_exists($this, 'onInit')) {
            app()->call([$this, 'onInit']);
        }
    }

    /**
     * @return array
     */
    public function defaultSettings()
    {
        return [];
    }

    /**
     * @param array $settings
     */
    public function setSettingsAttribute(array $settings)
    {
        $this->setSettings($settings);
        $this->attributes['settings'] = json_encode($this->{$this->getSettingsProperty()});
    }

    /**
     * @return array
     */
    public function getDirty()
    {
        $dirty = parent::getDirty();
        $dirty['settings'] = json_encode($this->{$this->getSettingsProperty()});

        return $dirty;
    }

    /**
     * @return string
     */
    protected function getSettingsProperty()
    {
        return 'sectionSettings';
    }

    /**
     * Create a new model instance that is existing.
     *
     * @param  array       $attributes
     * @param  string|null $connection
     *
     * @return static
     */
    public function newFromBuilder($attributes = [], $connection = null)
    {
        $model = $this->newInstance(['type' => array_get((array) $attributes, 'type')], true);
        $model->setRawAttributes((array) $attributes, true);
        $model->setConnection($connection ?: $this->connection);
        $model->initialize();

        return $model;
    }

    /**
     * Save a new model and return the instance.
     *
     * @param  array $attributes
     *
     * @return static
     */
    public static function create(array $attributes = [])
    {
        $model = static::getClassInstance((array) $attributes);
        $model->save();

        return $model;
    }

    /**
     * Create a new instance of the given model.
     *
     * @param  array $attributes
     * @param  bool  $exists
     *
     * @return static
     */
    public function newInstance($attributes = [], $exists = false)
    {
        $model = static::getClassInstance((array) $attributes);
        $model->exists = $exists;

        return $model;
    }

    /**
     * @param array $attributes
     *
     * @return static
     */
    public static function getClassInstance($attributes = [])
    {
        // This method just provides a convenient way for us to generate fresh model
        // instances of this current model. It is particularly useful during the
        // hydration of new objects via the Eloquent query builder instances.
        if (isset($attributes['type']) and ! is_null($class = static::getManagerClass()->getClassNameByType($attributes['type']))) {
            unset($attributes['type']);

            return new $class((array) $attributes);
        } else {
            return new static((array) $attributes);
        }
    }

    public function initialize()
    {
        if ($this->initialized) {
            return;
        }

        $this->setSettings((array) $this->settings);

        if (method_exists($this, 'onInit')) {
            app()->call([$this, 'onInit']);
        }

        $this->initialized = true;
    }

    /**
     * @throws Exception
     */
    public static function getManagerClass()
    {
        throw new Exception('Manager class not set');
    }
}
