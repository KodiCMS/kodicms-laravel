<div class="form-group">
	<div class="col-md-offset-3 col-md-9">
		<div class="checkbox">
			<label>
				{!! Form::hidden('settings[is_required]', 0) !!}
				{!! Form::switcher('settings[is_required]', 1, $field->isRequired(), [
					'id' => 'is_required'
				]) !!} @lang('datasource::core.field.required')
			</label>
		</div>
	</div>
</div>