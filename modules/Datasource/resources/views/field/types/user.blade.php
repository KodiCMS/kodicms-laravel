<div class="form-group">

	<div class="col-md-offset-3 col-md-9">
		<div class="checkbox">
			<label>
				{!! Form::hidden('settings[current_only]', 0) !!}
				{!! Form::switcher('settings[current_only]', 1, $field->isCurrentOnly(), [
					'id' => 'current_only'
				]) !!} @lang('datasource::fields.user.current_only')
			</label>
		</div>

		<div class="checkbox">
			<label>
				{!! Form::hidden('settings[set_current]', 0) !!}
				{!! Form::switcher('settings[set_current]', 1, $field->isCurrentSet(), [
				'id' => 'set_current'
				]) !!} @lang('datasource::fields.user.set_current')
			</label>
		</div>

		<div class="checkbox">
			<label>
				{!! Form::hidden('settings[is_unique]', 0) !!}
				{!! Form::switcher('settings[is_unique]', 1, $field->isUnique(), [
					'id' => 'unique'
				]) !!} @lang('datasource::fields.user.unique')
			</label>
		</div>
	</div>
</div>