<?php

namespace KodiCMS\Datasource\Sections\Images;

use KodiCMS\Datasource\Fields\File\Image;
use KodiCMS\Datasource\Sections\Document;
use KodiCMS\Datasource\FieldGroups\TitleGroup;
use KodiCMS\Datasource\Fields\Primitive\String;
use KodiCMS\Datasource\Fields\Primitive\Primary;
use KodiCMS\Datasource\Fields\Primitive\Timestamp;

class Section extends \KodiCMS\Datasource\Model\Section
{
    /**
     * @var string
     */
    protected $sectionTableName = 'images';

    /**
     * @return string
     */
    public function getDocumentClass()
    {
        return Document::class;
    }

    /**
     * @return array
     */
    public function getSystemFields()
    {
        return [
            new Primary([
                'key'      => 'id',
                'name'     => 'ID',
                'settings' => [
                    'headline_parameters' => [
                        'width'   => 30,
                        'visible' => true,
                    ],
                ],
            ]),
            (new TitleGroup)->setFields([
                new String([
                    'key'      => 'header',
                    'name'     => 'Header',
                    'settings' => [
                        'is_required'         => true,
                        'headline_parameters' => [
                            'visible' => true,
                        ],
                    ],
                ]),
            ]),
            new Image([
                'key'      => 'image',
                'name'     => 'Image',
                'settings' => [
                    'is_required'         => true,
                    'headline_parameters' => [
                        'visible' => true,
                    ],
                ],
            ]),
            new Timestamp([
                'key'      => static::CREATED_AT,
                'name'     => 'Created At',
                'settings' => [
                    'headline_parameters' => [
                        'width'   => 200,
                        'visible' => true,
                    ],
                ],
            ]),
            new Timestamp([
                'key'      => static::UPDATED_AT,
                'name'     => 'Updated At',
                'settings' => [
                    'headline_parameters' => [
                        'width'   => 200,
                        'visible' => false,
                    ],
                ],
            ]),
        ];
    }
}
