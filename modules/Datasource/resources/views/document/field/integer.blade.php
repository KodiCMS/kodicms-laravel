<div class="form-group form-inline">
	<label class="control-label col-md-2 col-sm-3" for="{{ $key }}">
		{{ $name }} @if($field->isRequired())*@endif
	</label>

	<div class="col-md-10 col-sm-9">
		{!! Form::text($key, $value, [
			'class' => 'form-control', 'id' => $key,
			'maxlength' => $field->getLength(), 'size' => $field->getLength()
		]) !!}

		@if($hint)
		<p class="help-block">{{ $hint }}</p>
		@endif
	</div>
</div>