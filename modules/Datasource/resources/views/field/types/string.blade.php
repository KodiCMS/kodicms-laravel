<div class="form-group">
	<div class="col-md-9 col-md-offset-3">
		<div class="checkbox">
			<label>
				{!! Form::hidden('settings[use_filemanager]', 0) !!}
				{!! Form::switcher('settings[use_filemanager]', 1, $field->isUseFilemanager(), [
				'id' => 'use_filemanager'
				]) !!} @lang('datasource::fields.string.use_filemanager')
			</label>
		</div>
	</div>
</div>

@include('datasource::field.partials.default', compact('field'))

<hr />

@include('datasource::field.partials.length', compact('field'))

<hr />

@include('datasource::field.partials.validation', compact('field'))