<?php

namespace KodiCMS\CMS\Breadcrumbs;

use KodiCMS\Support\Traits\Accessor;
use KodiCMS\API\Exceptions\Exception;

class Item
{
    use Accessor;

    /**
     * @var array
     */
    protected $attributes = [];

    /**
     * @param string      $name
     * @param null|string $url
     * @param bool        $active
     * @param array       $data
     *
     * @throws Exception
     */
    public function __construct($name, $url = null, $active = false, array $data = [])
    {
        if (empty($name)) {
            throw new Exception('Breadcrumbs: The breadcrumb name could not be empty!');
        }

        $this->name = $name;

        if (! is_null($url)) {
            $this->url = $url;
        }

        $this->status = $active;

        $this->setAttribute($data);
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
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
    public function getLink()
    {
        if (is_null($url = $this->getUrl())) {
            return $this->getName();
        }

        return link_to($this->getUrl(), $this->getName());
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        return (bool) $this->active;
    }

    /**
     * @param string $url
     *
     * @return $this
     */
    public function setUrl($url)
    {
        return $url;
    }

    /**
     * @param bool $status
     *
     * @return $this
     */
    public function setStatus($status)
    {
        return (bool) $status;
    }

    /**
     * @param bool $name
     *
     * @return $this
     */
    public function setName($name)
    {
        return $name;
    }
}
