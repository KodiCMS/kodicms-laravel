<?php namespace KodiCMS\Users\Model\FieldCollections;

use KodiCMS\Support\Model\Fields\RelatedField;
use UI;
use KodiCMS\Support\Model\Fields\TextField;
use KodiCMS\Support\Model\Fields\EmailField;
use KodiCMS\Support\Model\Fields\SelectField;
use KodiCMS\Support\Model\Fields\PasswordField;
use KodiCMS\Support\Model\Contracts\ModelFieldsInterface;

class UserFieldCollection implements ModelFieldsInterface
{
	/**
	 * @return array
	 */
	public function fields()
	{
		return [
			// Username
			(new TextField('username'))
				->setTitle(trans('users::core.field.username'))
				->setSettings([
					'append' => UI::icon('user'),
					'helpText' => trans('users::core.rule.username', ['num' => 3])
				])
				->group(function($group) {
					$group->setSizeLg();
				}),

			// Email
			(new EmailField('email'))
				->setTitle(trans('users::core.field.email'))
				->setSetting('append', UI::icon('envelope')),

			// Locale
			(new SelectField('locale', null, ['callbackOptions' => ['{model}', 'getAvailableLocales']]))
				->setTitle(trans('users::core.field.locale')),

			// Password
			(new PasswordField('password'))
				->setTitle(trans('users::core.field.password')),

			(new PasswordField('password_confirmation'))
				->setTitle(trans('users::core.field.password_confirm')),

			(new RelatedField('roles'))
				->setTitle(trans('users::core.field.roles'))
				->setSettings([
					'helpText' => trans('users::core.rule.roles')
				]),

		];
	}
}