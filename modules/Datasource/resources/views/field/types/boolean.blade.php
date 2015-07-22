<div class="form-group">
	<label class="control-label col-md-3">Style</label>

	<div class="col-md-3">
		{!! Form::select('settings[display]', $field->getDisplayStyles(), $field->getSetting('display')) !!}
	</div>
</div>
<hr />
<div class="form-group">
	<label class="control-label col-md-3">Default value</label>

	<div class="col-md-2">
		{!! Form::checkbox('settings[default_value]', 1, $field->getSetting('default_value'), [
			'class' => 'form-switcher', 'data-size' => 'small', 'data-width' => 60,
			'data-on' => trans('cms::system.button.on'),
			'data-off' => trans('cms::system.button.off'),
			'data-onstyle' => 'success'
		]) !!}
	</div>
</div>