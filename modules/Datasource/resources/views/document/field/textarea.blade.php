<div class="form-group">
	<label class="control-label col-md-2 col-sm-3" for="{{ $key }}">
		{{ $name }} @if($field->isRequired())*@endif
	</label>
	<div class="col-md-10 col-sm-9">
		{!! Form::textarea($key, $value, [
			'class' => 'form-control',
			'id' => $key,
			'rows' => $field->getRows(),
		]) !!}

		@if($hint)
		<p class="help-block">{{ $hint }}</p>
		@endif
	</div>
</div>