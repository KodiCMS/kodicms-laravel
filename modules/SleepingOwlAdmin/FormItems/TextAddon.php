<?php

namespace KodiCMS\SleepingOwlAdmin\FormItems;

class TextAddon extends NamedFormItem
{
    /**
     * @var string
     */
    protected $view = 'textaddon';

    /**
     * @var string
     */
    protected $placement = 'before';

    /**
     * @var string
     */
    protected $addon;

    /**
     * @param string|null $placement
     *
     * @return $this|string
     */
    public function placement($placement = null)
    {
        if (is_null($placement)) {
            return $this->placement;
        }
        $this->placement = $placement;

        return $this;
    }

    /**
     * @param string|null $addon
     *
     * @return $this|string
     */
    public function addon($addon = null)
    {
        if (is_null($addon)) {
            return $this->addon;
        }
        $this->addon = $addon;

        return $this;
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return parent::getParams() + [
            'placement' => $this->placement(),
            'addon'     => $this->addon(),
        ];
    }
}
