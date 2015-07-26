<div class="form-group">
	<label class="control-label col-md-3" for="position">@lang('datasource::core.field.position')</label>
	<div class="col-md-9 form-inline">
		{!! Form::text('position', null, [
				'id' => 'position',
				'class' => 'form-control',
				'size' => 4,
				'maxlength' => 4
		]) !!}
	</div>
</div>