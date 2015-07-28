<?php
$attributes = [
	'id' => $key,
	'maxlength' => $field->getLength(),
	'size' => $field->length,
	'class' => 'form-control'
];

if($field->isUseFilemanager())
{
	$attributes['data-filemanager'] = 'true';
}
?>

<div class="form-group @if($key == $section->getDocumentTitleKey()) form-group-lg @endif">
	<label class="control-label col-md-2 col-sm-3" for="{{ $key }}">
		{{ $name }} @if($field->isRequired())*@endif
	</label>
	<div class="col-md-10 col-sm-9">
		@if($field->isUseFilemanager())
		<div class="input-group">
		@endif

		{!! Form::text($key, $value, $attributes) !!}

		@if($field->isUseFilemanager())
		<div class="input-group-btn"></div>
		</div>
		@endif

		@if($hint)
		<p class="help-block">{{ $hint }}</p>
		@endif
	</div>
</div>