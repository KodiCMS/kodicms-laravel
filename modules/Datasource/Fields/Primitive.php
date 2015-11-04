<?php

namespace KodiCMS\Datasource\Fields;

use KodiCMS\Datasource\Model\Field;
use KodiCMS\Datasource\Contracts\DocumentInterface;

class Primitive extends Field
{
    /**
     * @param DocumentInterface $document
     * @param                   $value
     */
    public function onDocumentCreating(DocumentInterface $document, $value)
    {
        return $this->onDocumentUpdating($document, $value);
    }
}
