@include('datasource::field.partials.length', compact('field'))

<div class="form-group form-inline">
	<label class="control-label col-md-3" for="min">@lang('datasource::fields.integer.min')</label>

	<div class="col-md-9">
		{!! Form::text('settings[min]', $field->getMin(), [
			'class' => 'form-control', 'id' => 'min', 'size' => 10, 'maxlength' => 10
		]) !!}
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<label for="max">@lang('datasource::fields.integer.max')</label>
		&nbsp;&nbsp;&nbsp;
		{!! Form::text('settings[max]', $field->getMax(), [
			'class' => 'form-control', 'id' => 'min', 'size' => 10, 'maxlength' => 10
		]) !!}
	</div>
</div>

<hr />

@include('datasource::field.partials.default', compact('field'))

<hr />

<div class="form-group">
	<div class="col-md-offset-3 col-md-9">
		<div class="checkbox">
			<label>
				{!! Form::hidden('settings[auto_increment]', 0) !!}
				{!! Form::switcher('settings[auto_increment]', 1, $field->isAutoIncrementable(), [
					'id' => 'auto_increment'
				]) !!} @lang('datasource::fields.integer.auto_increment')
			</label>
		</div>
	</div>
</div>

<div class="form-group form-inline">
	<label class="control-label col-md-3" for="increment_step">@lang('datasource::fields.integer.increment_step')</label>

	<div class="col-md-9">
		{!! Form::text('settings[increment_step]', $field->getIncrementStep(), [
			'class' => 'form-control', 'id' => 'increment_step', 'size' => 5, 'maxlength' => 5
		]) !!}
	</div>
</div>