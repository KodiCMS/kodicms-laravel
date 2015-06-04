<?php namespace KodiCMS\Support\Model\Fields;

class CheckboxField extends KodiCMSField
{
	/**
	 * @param string $name
	 * @param mixed $value
	 * @param array $attributes
	 * @return mixed
	 */
	protected function getFormFieldHTML($name, $value, array $attributes)
	{
		return Form::checkbox($name, 1, $value, $attributes);
	}
}