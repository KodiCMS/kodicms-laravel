<div class="form-group">
	<label class="control-label col-md-2 col-sm-3" for="{{ $field->getDBKey() }}">
		{{ $field->getName() }} @if($field->isRequired())*@endif
	</label>
	<div class="col-md-10 col-sm-9">
		<div class="input-group">
			<div class="input-group-addon" data-icon="envelope"></div>
			{!! Form::text($field->getDBKey(), $value, [
				'class' => 'form-control', 'id' => $field->getDBKey(),
				'maxlength' => 60, 'size' => 60
			]) !!}
		</div>

		@if($hint = $field->getHint()): ?>
		<p class="help-block">{{ $hint }}</p>
		@endif
	</div>
</div>