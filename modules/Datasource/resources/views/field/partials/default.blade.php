<div class="form-group">
	<label class="control-label col-md-3" for="primitive_default">@lang('datasource::core.field.default_value')</label>

	<div class="col-md-9">
		{!! Form::text('settings[default_value]', $field->getDefaultValue(), ['class' => 'form-control', 'id' => 'default_value']) !!}
	</div>
</div>