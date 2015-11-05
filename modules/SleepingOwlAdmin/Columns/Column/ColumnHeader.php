<?php

namespace KodiCMS\SleepingOwlAdmin\Columns\Column;

use Illuminate\Contracts\Support\Renderable;

class ColumnHeader implements Renderable
{
    /**
     * Header title.
     * @var string
     */
    protected $title;

    /**
     * Is column orderable?
     * @var bool
     */
    protected $orderable = true;

    /**
     * Get or set title.
     *
     * @param string|null $title
     *
     * @return $this|string
     */
    public function title($title = null)
    {
        if (is_null($title)) {
            return $this->title;
        }
        $this->title = $title;

        return $this;
    }

    /**
     * Get or set column orderable feature.
     *
     * @param bool|null $orderable
     *
     * @return $this|bool
     */
    public function orderable($orderable = null)
    {
        if (is_null($orderable)) {
            return $this->orderable;
        }
        $this->orderable = $orderable;

        return $this;
    }

    /**
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function render()
    {
        return app('sleeping_owl.template')->view('column.header', [
            'title'     => $this->title(),
            'orderable' => $this->orderable(),
        ]);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->render();
    }
}
