<?php

namespace KodiCMS\SleepingOwlAdmin\Interfaces;

use Illuminate\Database\Eloquent\Model;

interface ColumnInterface
{
    /**
     * Initialize column.
     */
    public function initialize();

    /**
     * @param Model $model
     *
     * @return $this
     */
    public function setModel(Model $model);
}
