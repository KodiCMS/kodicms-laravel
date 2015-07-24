<div class="form-group">
	<div class="col-md-offset-3 col-md-9">
		<div class="checkbox">
			<label>
				{!! Form::switcher('settings[is_required]', 1, $field->isRequired(), [
					'id' => 'is_required'
				]) !!} Required
			</label>
		</div>
	</div>
</div>