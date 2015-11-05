<?php

namespace KodiCMS\SleepingOwlAdmin\Columns\Column;

class Control extends BaseColumn
{
    /**
     * Column view.
     * @var string
     */
    protected $view = 'control';

    /**
     *
     */
    public function __construct()
    {
        parent::__construct();

        $this->orderable(false);
    }

    /**
     * Check if instance supports soft-deletes and trashed.
     * @return bool
     */
    protected function trashed()
    {
        if (method_exists($this->instance, 'trashed')) {
            return $this->instance->trashed();
        }

        return false;
    }

    /**
     * Check if instance editable.
     * @return bool
     */
    protected function editable()
    {
        return ! $this->trashed() && ! is_null($this->model()->edit($this->instance->getKey()));
    }

    /**
     * Get instance edit url.
     * @return string
     */
    protected function editUrl()
    {
        return $this->model()->editUrl($this->instance->getKey());
    }

    /**
     * Check if instance is deletable.
     * @return bool
     */
    protected function deletable()
    {
        return ! $this->trashed() && ! is_null($this->model()->delete($this->instance->getKey()));
    }

    /**
     * Get instance delete url.
     * @return string
     */
    protected function deleteUrl()
    {
        return $this->model()->deleteUrl($this->instance->getKey());
    }

    /**
     * Check if instance is restorable.
     * @return bool
     */
    protected function restorable()
    {
        return $this->trashed() && ! is_null($this->model()->restore($this->instance->getKey()));
    }

    /**
     * Get instance restore url.
     * @return string
     */
    protected function restoreUrl()
    {
        return $this->model()->restoreUrl($this->instance->getKey());
    }

    /**
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function render()
    {
        return app('sleeping_owl.template')->view('column.'.$this->view, [
            'editable'   => $this->editable(),
            'editUrl'    => $this->editUrl(),
            'deletable'  => $this->deletable(),
            'deleteUrl'  => $this->deleteUrl(),
            'restorable' => $this->restorable(),
            'restoreUrl' => $this->restoreUrl(),
        ]);
    }
}
