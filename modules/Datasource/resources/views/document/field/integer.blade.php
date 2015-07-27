<div class="form-group form-inline">
	<label class="control-label col-md-2 col-sm-3" for="{{ $field->getDBKey() }}">
		{{ $field->getName() }} @if($field->isRequired())*@endif
	</label>

	<div class="col-md-10 col-sm-9">
		{!! Form::text($field->getDBKey(), $value, [
			'class' => 'form-control', 'id' => $field->getDBKey(),
			'maxlength' => $field->getLength(), 'size' => $field->getLength()
		]) !!}

		@if($hint = $field->getHint()): ?>
		<p class="help-block">{{ $hint }}</p>
		@endif
	</div>
</div>