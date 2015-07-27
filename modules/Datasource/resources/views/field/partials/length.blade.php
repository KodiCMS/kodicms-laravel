<div class="form-group form-inline">
	<label class="control-label col-md-3" for="length">@lang('datasource::fields.string.length')</label>

	<div class="col-md-2">
		{!! Form::text('settings[length]', $field->getLength(), ['class' => 'form-control', 'id' => 'length', 'size' => 3, 'maxlength' => 3]) !!}
	</div>
</div>