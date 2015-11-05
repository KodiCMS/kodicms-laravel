<?php

namespace KodiCMS\SleepingOwlAdmin\Columns\Column;

use Closure;

class Custom extends BaseColumn
{
    /**
     * Callback to render column contents.
     * @var Closure
     */
    protected $callback;

    /**
     * Get or set callback.
     *
     * @param Closure|null $callback
     *
     * @return $this|Closure
     */
    public function callback($callback = null)
    {
        if (is_null($callback)) {
            return $this->callback;
        }
        $this->callback = $callback;

        return $this;
    }

    /**
     * Get value from callback.
     *
     * @param mixed $instance
     *
     * @return mixed
     * @throws \Exception
     */
    protected function getValue($instance)
    {
        if (! is_callable($this->callback())) {
            throw new \Exception('Invalid custom column callback');
        }

        return call_user_func($this->callback(), $instance);
    }

    /**
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     * @throws \Exception
     */
    public function render()
    {
        return app('sleeping_owl.template')->view('column.custom', [
            'value'  => $this->getValue($this->instance),
            'append' => $this->append(),
        ]);
    }
}
