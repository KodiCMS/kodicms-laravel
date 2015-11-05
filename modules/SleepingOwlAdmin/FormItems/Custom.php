<?php

namespace KodiCMS\SleepingOwlAdmin\FormItems;

use Closure;

class Custom extends BaseFormItem
{
    /**
     * @var string|Closure
     */
    protected $display;

    /**
     * @var Closure
     */
    protected $callback;

    /**
     * @param string|Closure|null $display
     *
     * @return $this|mixed
     */
    public function display($display = null)
    {
        if (is_null($display)) {
            if (is_callable($this->display)) {
                return call_user_func($this->display, $this->instance());
            }

            return $this->display;
        }
        $this->display = $display;

        return $this;
    }

    /**
     * @param Closure|null $callback
     *
     * @return $this
     */
    public function callback(Closure $callback = null)
    {
        if (is_null($callback)) {
            return $this->callback;
        }
        $this->callback = $callback;

        return $this;
    }

    /**
     * @return $this|Custom|mixed
     */
    public function render()
    {
        return $this->display();
    }

    public function save()
    {
        $callback = $this->callback();
        if (is_callable($callback)) {
            call_user_func($callback, $this->instance());
        }
    }
}
