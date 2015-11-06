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
        $this->setOrderable(false);
    }

    /**
     * @return mixed
     */
    protected function getModelKey()
    {
        return $this->getModel()->getKey();
    }

    /**
     * Check if instance supports soft-deletes and trashed.
     * @return bool
     */
    protected function isTrashed()
    {
        if (method_exists($this->getModel(), 'trashed')) {
            return $this->getModel()->trashed();
        }

        return false;
    }

    /**
     * Check if instance editable.
     * @return bool
     */
    protected function editable()
    {
        return ! $this->isTrashed() && ! is_null($this->getModelConfiguration()->fireEdit($this->getModelKey()));
    }

    /**
     * Get instance edit url.
     * @return string
     */
    protected function editUrl()
    {
        return $this->getModelConfiguration()->getEditUrl($this->getModelKey());
    }

    /**
     * Check if instance is deletable.
     * @return bool
     */
    protected function deletable()
    {
        return ! $this->isTrashed() && ! is_null($this->getModelConfiguration()->fireDelete($this->getModelKey()));
    }

    /**
     * Get instance delete url.
     * @return string
     */
    protected function deleteUrl()
    {
        return $this->getModelConfiguration()->getDeleteUrl($this->getModelKey());
    }

    /**
     * Check if instance is restorable.
     * @return bool
     */
    protected function restorable()
    {
        return $this->isTrashed() && ! is_null($this->getModelConfiguration()->fireRestore($this->getModelKey()));
    }

    /**
     * Get instance restore url.
     * @return string
     */
    protected function restoreUrl()
    {
        return $this->getModelConfiguration()->getRestoreUrl($this->getModelKey());
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
