<div class="form-group">
	<label class="control-label col-md-3">@lang('datasource::fields.boolean.style')</label>

	<div class="col-md-3">
		{!! Form::select('settings[display]', $field->getDisplayStyles(), $field->getSetting('display')) !!}
	</div>
</div>
<hr />
<div class="form-group">
	<div class="col-md-9 col-md-offset-3">
		<div class="checkbox">
			<label>
				{!! Form::hidden('settings[default_value]', 0) !!}
				{!! Form::switcher('settings[default_value]', 1, $field->getDefaultValue(), [
				'id' => 'default_value'
				]) !!} @lang('datasource::core.field.default_value')
			</label>
		</div>
	</div>
</div>