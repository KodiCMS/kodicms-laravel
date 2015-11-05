<?php

namespace KodiCMS\SleepingOwlAdmin\Columns\Column;

use Meta;
use Illuminate\Contracts\Support\Renderable;
use KodiCMS\SleepingOwlAdmin\Model\ModelConfiguration;
use KodiCMS\SleepingOwlAdmin\Interfaces\ColumnInterface;

abstract class BaseColumn implements Renderable, ColumnInterface
{
    /**
     * Column header.
     * @var ColumnHeader
     */
    protected $header;

    /**
     * Model instance currently rendering.
     * @var mixed
     */
    protected $instance;

    /**
     * Column appendant.
     * @var ColumnInterface
     */
    protected $append;

    /**
     *
     */
    public function __construct()
    {
        $this->header = new ColumnHeader;
    }

    /**
     * Initialize column.
     */
    public function initialize()
    {
        Meta::loadPackage(get_class());
    }

    /**
     * Get related model configuration.
     * @return ModelConfiguration
     */
    protected function model()
    {
        return app('sleeping_owl.admin')->getModel(get_class($this->instance));
    }

    /**
     * Set column header label.
     *
     * @param string $title
     *
     * @return $this
     */
    public function label($title)
    {
        $this->header->title($title);

        return $this;
    }

    /**
     * Enable/disable column orderable feature.
     *
     * @param bool $orderable
     *
     * @return $this
     */
    public function orderable($orderable)
    {
        $this->header->orderable($orderable);

        return $this;
    }

    /**
     * Check if column is orderable.
     * @return bool
     */
    public function isOrderable()
    {
        return $this->header()->orderable();
    }

    /**
     * Get column header.
     * @return ColumnHeader
     */
    public function header()
    {
        return $this->header;
    }

    /**
     * Get or set column appendant.
     *
     * @param ColumnInterface|null $append
     *
     * @return $this|ColumnInterface
     */
    public function append($append = null)
    {
        if (is_null($append)) {
            return $this->append;
        }
        $this->append = $append;

        return $this;
    }

    /**
     * Set currently rendering instance.
     *
     * @param mixed $instance
     *
     * @return $this
     */
    public function setInstance($instance)
    {
        $this->instance = $instance;
        if (! is_null($this->append()) && ($this->append() instanceof ColumnInterface)) {
            $this->append()->setInstance($instance);
        }

        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->render();
    }
}
