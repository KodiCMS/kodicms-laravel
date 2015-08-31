<div class="form-group">
	<label class="control-label col-md-2 col-sm-3" for="{{ $key }}">
		{{ $name }} @if($field->isRequired())*@endif
	</label>

	<div class="col-md-10 col-sm-9">
		{!! Form::text($key, $value, [
			'id' => $key, 'class' => 'tags-input form-control',
			'data-related-id' => $field->getRelatedSectionId()
		]) !!}
		@if($hint)
		<p class="help-block">{{ $hint }}</p>
		@endif
	</div>
</div>
