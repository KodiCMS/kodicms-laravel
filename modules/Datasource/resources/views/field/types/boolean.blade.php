<div class="form-group">
	<label class="control-label col-md-3">Style</label>

	<div class="col-md-3">
		{!! Form::select('settings[display]', $field->getDisplayStyles(), $field->getSetting('display')) !!}
	</div>
</div>
<hr />
<div class="form-group">
	<div class="col-md-9 col-md-offset-3">
		<div class="checkbox">
			<label>
				{!! Form::switcher('settings[default_value]', 1, $field->getDefaultValue(), [
				'id' => 'default_value'
				]) !!} Default value
			</label>
		</div>
	</div>
</div>