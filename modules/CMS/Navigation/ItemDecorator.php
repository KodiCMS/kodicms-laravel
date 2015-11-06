<?php

namespace KodiCMS\CMS\Navigation;

use UI;
use Illuminate\Support\Str;
use KodiCMS\Support\Traits\Accessor;

/**
 * Class ItemDecorator.
 *
 * @method setIcon($icon)
 */
class ItemDecorator
{
    use Accessor;

    /**
     * @var array
     */
    protected $attributes = [
        'permissions' => null,
    ];

    /**
     * @var Section
     */
    protected $sectionObject;

    /**
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $this->setAttribute($data);
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        return (bool) $this->getAttribute('status', false);
    }

    /**
     * @return bool
     */
    public function isHidden()
    {
        return (bool) $this->getAttribute('hidden', false);
    }

    /**
     * @return string
     */
    public function getIcon()
    {
        if (! isset($this->icon)) {
            return;
        }

        return UI::icon($this->icon.' menu-icon');
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->getAttribute('name');
    }

    /**
     * @return string
     */
    public function getName()
    {
        $label = $this->getLabel();

        return is_null($label) ? $this->getAttribute('name') : $label;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        if (! is_null($label = $this->getAttribute('label'))) {
            return trans($label);
        }

        return;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->getAttribute('url');
    }

    /**
     * @return array
     */
    public function getPermissions()
    {
        return (array) $this->getAttribute('premissions');
    }

    /**
     * @param bool $status
     *
     * @return $this
     */
    public function setStatus($status = true)
    {
        if ($this->getSection() instanceof Section) {
            $this->getSection()->setStatus((bool) $status);
        }

        return (bool) $status;
    }

    /**
     * @param Section $section
     *
     * @return $this
     */
    public function setSection(Section &$section)
    {
        $this->sectionObject = $section;

        return $this;
    }

    /**
     * @return Section
     */
    public function getSection()
    {
        return $this->sectionObject;
    }

    /**
     * is triggered when invoking inaccessible methods in an object context.
     *
     * @param $name      string
     * @param $arguments array
     *
     * @return mixed
     * @link http://php.net/manual/en/language.oop5.overloading.php#language.oop5.overloading.methods
     */
    public function __call($name, $arguments)
    {
        if (Str::startsWith($name, 'set') and count($arguments) === 1) {
            $method = substr($name, 3);

            return $this->setAttribute(strtolower($method), $arguments[0]);
        }
    }
}
