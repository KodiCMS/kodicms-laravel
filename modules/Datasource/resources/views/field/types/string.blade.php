<div class="form-group">
	<div class="col-md-9 col-md-offset-3">
		<div class="checkbox">
			<label>
				{!! Form::checkbox('settings[use_filemanager]', 1, $field->getSetting('use_filemanager') == 1, ['id' => 'use_filemanager']) !!}
				Use filemanager to get data
			</label>
		</div>
	</div>
</div>

<div class="form-group">
	<label class="control-label col-md-3" for="primitive_default">Default value</label>

	<div class="col-md-9">
		{!! Form::text('settings[default_value]', $field->getSetting('default_value'), ['class' => 'form-control', 'id' => 'default_value']) !!}
	</div>
</div>

<hr />

<div class="form-group form-inline">
	<label class="control-label col-md-3" for="length">Field length</label>

	<div class="col-md-2">
		{!! Form::text('settings[length]', $field->getSetting('length'), ['class' => 'form-control', 'id' => 'length', 'size' => 3, 'maxlength' => 3]) !!}
	</div>
</div>