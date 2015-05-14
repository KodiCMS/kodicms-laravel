<?php namespace KodiCMS\Widgets\Services;

use Validator;
use KodiCMS\CMS\Contracts\ModelUpdator;
use KodiCMS\Widgets\Exceptions\WidgetException;
use KodiCMS\Widgets\Model\Widget;

class WidgetUpdator implements ModelUpdator
{
	/**
	 * Get a validator for an incoming registration request.
	 *
	 * @param  array $data
	 * @return \Illuminate\Contracts\Validation\Validator
	 */
	public function validator($id, array $data)
	{
		$validator = Validator::make($data, [
			'name' => 'required|max:255'
		]);

		return $validator;
	}

	/**
	 * Update a new widget instance after a validation.
	 *
	 * @param int $id
	 * @param  array $data
	 * @return Widget
	 */
	public function update($id, array $data)
	{
		$widget = Widget::findOrFail($id);

		$widget->update(array_except($data, ['type']));

		return $widget;
	}
}