<?php

namespace KodiCMS\Widgets\Contracts;

interface WidgetPaginator extends WidgetRenderable
{
    const ORDER_ASC = 'ASC';
    const ORDER_DESC = 'DESC';

    /**
     * @return int
     */
    public function getTotalDocuments();
}
