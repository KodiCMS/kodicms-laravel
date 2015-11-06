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
     * @return Closure
     */
    public function getCallback()
    {
        return $this->callback;
    }

    /**
     * @param Closure $callback
     *
     * @return $this
     */
    public function setCallback(Closure $callback)
    {
        $this->callback = $callback;

        return $this;
    }

    /**
     * Get value from callback.
     *
     * @param Model $model
     *
     * @return mixed
     * @throws \Exception
     */
    protected function getValue(Model $model)
    {
        if (! is_callable($callback = $this->getCallback())) {
            throw new \Exception('Invalid custom column callback');
        }

        return call_user_func($callback, $model);
    }

    /**
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     * @throws \Exception
     */
    public function render()
    {
        return app('sleeping_owl.template')->view('column.custom', [
            'value'  => $this->getValue($this->getModel()),
            'append' => $this->getAppend(),
        ]);
    }
}
