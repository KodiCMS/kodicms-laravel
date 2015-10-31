<?php
namespace KodiCMS\CMS\Navigation;

use UI;
use KodiCMS\Support\Traits\Accessor;

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
     * @return boolean
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
        if ( ! isset( $this->icon )) {
            return null;
        }

        return UI::icon($this->icon . ' menu-icon');
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
        if (( $label = $this->getAttribute('label') ) !== null) {
            return trans($label);
        }

        return null;
    }


    /**
     * @return string
     */
    public function getUrl()
    {
        return url($this->getAttribute('url'));
    }


    /**
     * @return array
     */
    public function getPermissions()
    {
        return (array) $this->getAttribute('premissions');
    }


    /**
     * @param boolean $status
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
    public function setSection(Section & $section)
    {
        $this->sectionObject = $section;

        return $this;
    }


    /**
     *
     * @return Section
     */
    public function getSection()
    {
        return $this->sectionObject;
    }
}