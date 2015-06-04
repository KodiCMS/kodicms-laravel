<?php namespace KodiCMS\Support\Model\Fields;

class DateTimeField extends TextField
{
	protected function boot()
	{
		parent::boot();

		$this->setAttributes([
			'class' => 'datetimepicker'
		]);
	}
}