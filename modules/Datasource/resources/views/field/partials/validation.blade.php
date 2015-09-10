<div class="form-group">
	<label class="control-label col-md-3" for="primitive_default">@lang('datasource::core.field.validation')</label>

	<div class="col-md-9">
		{!! Form::text('settings[validation_rules]', $field->getSetting('validation_rules'), ['class' => 'form-control', 'id' => 'validation_rules']) !!}
		<div class="help-block">{!! link_to('http://laravel.com/docs/5.1/validation#available-validation-rules') !!}</div>
	</div>
</div>