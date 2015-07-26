<div class="form-group">
	<div class="col-md-9 col-md-offset-3">
		<div class="checkbox">
			<label>
				{!! Form::switcher('settings[use_filemanager]', 1, $field->isUseFilemanager(), [
				'id' => 'use_filemanager'
				]) !!} @lang('datasource::fields.string.use_filemanager')
			</label>
		</div>
	</div>
</div>

<div class="form-group">
	<label class="control-label col-md-3" for="primitive_default">@lang('datasource::core.field.default_value')</label>

	<div class="col-md-9">
		{!! Form::text('settings[default_value]', $field->getDefaultValue(), ['class' => 'form-control', 'id' => 'default_value']) !!}
	</div>
</div>

<hr />

<div class="form-group form-inline">
	<label class="control-label col-md-3" for="length">@lang('datasource::fields.string.length')</label>

	<div class="col-md-2">
		{!! Form::text('settings[length]', $field->getLength(), ['class' => 'form-control', 'id' => 'length', 'size' => 3, 'maxlength' => 3]) !!}
	</div>
</div>