<?php namespace KodiCMS\Widgets\Services;

use Validator;
use KodiCMS\CMS\Contracts\ModelCreator;
use KodiCMS\Widgets\Exceptions\WidgetException;
use KodiCMS\Widgets\Manager\WidgetManager;
use KodiCMS\Widgets\Model\Widget;

class WidgetCreator implements ModelCreator
{
	/**
	 * Get a validator for an incoming registration request.
	 *
	 * @param  array $data
	 * @return \Illuminate\Contracts\Validation\Validator
	 */
	public function validator(array $data)
	{
		$validator = Validator::make($data, [
			'name' => 'required|max:255',
			'type' => 'required'
		]);

		return $validator;
	}

	/**
	 * Create a new widget instance after a validation.
	 *
	 * @param  array $data
	 * @return Widget
	 * @throws WidgetException
	 */
	public function create(array $data)
	{
		$type = array_get($data, 'type');
		return Widget::create($data);
	}
}