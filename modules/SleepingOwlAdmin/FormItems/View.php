<?php

namespace KodiCMS\SleepingOwlAdmin\FormItems;

class View extends Custom
{
    /**
     * @var string
     */
    protected $view;

    /**
     * @param string $view
     */
    public function __construct($view)
    {
        $this->view($view);
    }

    /**
     * @param string|null $view
     *
     * @return $this|string
     */
    public function view($view = null)
    {
        if (is_null($view)) {
            return $this->view;
        }
        $this->view = $view;
        $this->display(function ($instance) {
            return view($this->view(), ['instance' => $instance]);
        });

        return $this;
    }

    public function save()
    {
        $callback = $this->callback();
        if (is_callable($callback)) {
            call_user_func($callback, $this->instance());
        }
    }
}
