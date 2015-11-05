<?php

namespace KodiCMS\CMS\Wysiwyg;

use Meta;
use Illuminate\Contracts\Support\Arrayable;
use KodiCMS\CMS\Contracts\WysiwygEditorInterface;
use KodiCMS\CMS\Contracts\WysiwygFilterInterface;

class WysiwygEditor implements WysiwygEditorInterface, Arrayable
{
    /**
     * @var string
     */
    protected $id;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var WysiwygFilterInterface
     */
    protected $filter;

    /**
     * @var string
     */
    protected $packageName;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var bool
     */
    protected $used = false;

    /**
     * @param string       $id
     * @param string|null  $name
     * @param string|null  $filter
     * @param string| null $package
     * @param string       $type
     */
    public function __construct($id, $name = null, $filter = null, $package = null, $type = null)
    {
        $this->id = $id;

        $this->name = $name === null
            ? studly_case($id)
            : $name;

        $this->type = $type == WysiwygManager::TYPE_HTML
            ? WysiwygManager::TYPE_HTML
            : WysiwygManager::TYPE_CODE;

        $this->filter = is_null($filter)
            ? $this->loadDefaultFilter()
            : $this->loadFilter($filter);

        $this->packageName = $package === null
            ? $id
            : $package;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return WysiwygFilterInterface
     */
    public function getFilter()
    {
        return $this->filter;
    }

    /**
     * @return bool
     */
    public function isUsed()
    {
        return $this->used;
    }

    /**
     * @param string $text
     *
     * @return string
     */
    public function applyFilter($text)
    {
        return $this->getFilter()->apply($text);
    }

    /**
     * @return bool
     */
    public function load()
    {
        Meta::loadPackage($this->packageName);

        return $this->used = true;
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'id'           => $this->getId(),
            'name'         => $this->getName(),
            'type'         => $this->getType(),
            'assetPackage' => $this->packageName,
            'filter'       => get_class($this->getFilter()),
        ];
    }

    /**
     * @param string $filter
     *
     * @return WysiwygFilterInterface
     */
    protected function loadFilter($filter)
    {
        if (class_exists($filter) and (new ReflectionClass($filter))->implementsInterface(WysiwygFilterInterface::class)) {
            return app()->make($filter);
        }

        return $this->loadDefaultFilter();
    }

    /**
     * @return WysiwygFilterInterface
     */
    protected function loadDefaultFilter()
    {
        return app()->make(WysiwygDummyFilter::class);
    }
}
