<?php

namespace KodiCMS\Pages\Model\FieldCollections;

use KodiCMS\Support\Model\Fields\RelatedField;
use KodiCMS\Support\Model\Fields\UserField;
use KodiCMS\Support\Model\Fields\SlugField;
use KodiCMS\Support\Model\Fields\TextField;
use KodiCMS\Support\Model\Fields\SelectField;
use KodiCMS\Support\Model\Fields\DateTimeField;
use KodiCMS\Support\Model\Fields\CheckboxField;
use KodiCMS\Support\Model\Contracts\ModelFieldsInterface;

class PageFieldCollection implements ModelFieldsInterface
{
    /**
     * @return array
     */
    public function fields()
    {
        return [
            // Title
            (new TextField('title', ['class' => 'slug-generator']))
                ->setTitle(trans('pages::core.field.title'))->group(function (
                    $group
                ) {
                    $group->setSizeLg();
                }),
            // Slug
            (new SlugField('slug'))->setTitle(trans('pages::core.field.slug')),
            // Is Redirect
            (new CheckboxField('is_redirect'))->setTitle(trans('pages::core.field.is_redirect')),
            // Redirect Url
            (new TextField('redirect_url'))->setTitle(trans('pages::core.field.redirect_url'))->group(function ($group
                ) {
                    $group->setAttribute('id', 'redirect-to-container');
                }),
            // Breadcrumb
            (new TextField('breadcrumb'))->setTitle(trans('pages::core.field.breadcrumb')),
            // Meta title
            (new TextField('meta_title'))->setTitle(trans('pages::core.field.meta_title')),
            // Meta keywords
            (new TextField('meta_keywords'))->setTitle(trans('pages::core.field.meta_keywords')),
            // Meta description
            (new TextField('meta_description'))->setTitle(trans('pages::core.field.meta_description')),
            // Create date
            (new DateTimeField('created_at'))->setTitle(trans('pages::core.field.created_at')),
            // Update date
            (new DateTimeField('updated_at'))->setTitle(trans('pages::core.field.updated_at')),
            // Publish date
            (new DateTimeField('published_at'))->setTitle(trans('pages::core.field.published_at')),
            // Status
            (new SelectField('status', null, ['callbackOptions' => '{model}::getStatusList']))
                ->setTitle(trans('pages::core.field.status')),
            // Parent ID
            (new SelectField('parent_id', null, [
                'callbackOptions' => [
                    '{model}',
                    'getSitemap',
                ],
            ]))->setTitle(trans('pages::core.field.parent_id')),
            // Parent ID
            (new RelatedField('parent', null, [
                'callbackOptions' => [
                    '{model}',
                    'getSitemap',
                ],
            ]))->setTitle(trans('pages::core.field.parent_id')),
            // layout
            (new SelectField('layout_file', null, [
                'callbackOptions' => [
                    '{model}',
                    'getLayoutList',
                ],
            ]))->setTitle(trans('pages::core.field.layout_file'))->group(function ($group) {
                    $group->setTemplate('pages::pages.model_fields.layout_file');
                }),
            // Behavior
            (new SelectField('behavior', null, ['callbackOptions' => 'KodiCMS\Pages\Behavior\Manager::formChoices']))
                ->setTitle(trans('pages::core.field.behavior'))->group(function (
                    $group
                ) {
                    $group->setTemplate('pages::pages.model_fields.behavior');
                }),
            // Robots
            (new SelectField('robots', null, [
                'callbackOptions' => [
                    '{model}',
                    'getRobotsList',
                ],
            ]))->setTitle(trans('pages::core.field.robots'))->group(function ($group) {
                    $group->setSettings([
                        'fieldCol' => 'col-md-6',
                    ]);
                }),
            // Creator
            (new UserField('created_by_id'))->setModelKey('createdBy')->setTitle(trans('pages::core.field.created_by_id')),
            // Updator
            (new UserField('updated_by_id'))->setModelKey('updatedBy')->setTitle(trans('pages::core.field.updated_by_id')),
        ];
    }
}
