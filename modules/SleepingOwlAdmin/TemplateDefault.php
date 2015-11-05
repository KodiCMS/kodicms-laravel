<?php

namespace KodiCMS\SleepingOwlAdmin;

use KodiCMS\SleepingOwlAdmin\Interfaces\TemplateInterface;

class TemplateDefault implements TemplateInterface
{
    /**
     * @return string
     */
    public function getViewNamespace()
    {
        return 'sleepingowladmin::';
    }

    /**
     * @param string $view
     *
     * @return string
     */
    public function getTemplateViewPath($view)
    {
        return $this->getViewNamespace().'default.'.$view;
    }

    /**
     * @param string $view
     * @param array  $data
     * @param array  $mergeData
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function view($view, $data = [], $mergeData = [])
    {
        return view($this->getTemplateViewPath($view), $data, $mergeData);
    }
}
