<?php namespace Plugins\butschster\News\Model\FieldCollections;

use KodiCMS\Support\Model\Fields\SlugField;
use KodiCMS\Support\Model\Fields\TextField;
use KodiCMS\Support\Model\Fields\UserField;
use KodiCMS\Support\Model\Fields\RelatedField;
use KodiCMS\Support\Model\Fields\DateTimeField;
use KodiCMS\Support\Model\Contracts\ModelFieldsInterface;

class NewsFieldCollection implements ModelFieldsInterface
{
	/**
	 * @return array
	 */
	public function fields()
	{
		return [
			// Title
			(new TextField('title', ['class' => 'slug-generator']))
				->setTitle(trans('butschster:news::core.field.title'))
				->group(function($group) {
					$group->setSizeLg();
				}),

			// Slug
			(new SlugField('slug'))->setTitle(trans('butschster:news::core.field.slug')),

			// Creator
			(new UserField('user_id'))->setModelKey('user')->setTitle(trans('butschster:news::core.field.user')),

			// Create date
			(new DateTimeField('created_at'))->setTitle(trans('butschster:news::core.field.created_at')),

			// Update date
			(new DateTimeField('updated_at'))->setTitle(trans('butschster:news::core.field.updated_at')),

			// Related content
			new RelatedField('content')
		];
	}
}