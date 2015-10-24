<div class="form-group">
	<label class="control-label col-md-2 col-sm-3" for="{{ $key }}">
		{{ $name }} @if($field->isRequired())*@endif
	</label>

	<div class="col-md-10 col-sm-9">
		@if ($field->isCurrentOnly())
		{!! Form::hidden($key, $value) !!}
		{!! Form::select('', $field->getUserList($document), $value, ['disabled', 'class' => 'form-control', 'style' => 'width: 250px;']) !!}
		@else
		{!! Form::select($key, $field->getUserList($document), $value, ['class' => 'form-control', 'style' => 'width: 250px;']) !!}
		@endif

		@if($hint)
		<p class="help-block">{{ $hint }}</p>
		@endif
	</div>
</div>