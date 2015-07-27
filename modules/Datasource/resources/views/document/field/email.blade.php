<div class="form-group">
	<label class="control-label col-md-2 col-sm-3" for="{{ $key }}">
		{{ $name }} @if($field->isRequired())*@endif
	</label>
	<div class="col-md-10 col-sm-9">
		<div class="input-group">
			<div class="input-group-addon" data-icon="envelope"></div>
			{!! Form::text($key, $value, [
				'class' => 'form-control', 'id' => $key,
				'maxlength' => 60, 'size' => 60
			]) !!}
		</div>

		@if($hint)
		<p class="help-block">{{ $hint }}</p>
		@endif
	</div>
</div>