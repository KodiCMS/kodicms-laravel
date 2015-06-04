<?php namespace KodiCMS\Support\Model\Fields;

use KodiCMS\Support\Model\ModelField;

abstract class KodiCMSField extends ModelField
{
	protected function boot()
	{
		$this->getFieldAttributes()->setAttributes([
			'class' => ['form-control']
		]);

		$this->getLabelAttributes()->setAttributes([
			'class' => ['control-label', 'col-md-3']
		]);
	}

	/**
	 * @param array $attributes
	 * @return string
	 */
	public function renderFormField(array $attributes = [])
	{
		$attributes['tabindex'] = $this->getTabIndex();

		return parent::renderFormField($attributes);
	}
}