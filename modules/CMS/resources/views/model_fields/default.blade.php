<div {!! HTML::attributes($group->getAttributes()) !!}>
	{!! $label->render(['class' => $group->labelCol ]) !!}

	<div class="{{ $group->fieldCol }}">
		@if($field->hasAddInputGroup())
		<div class="input-group">
		@endif

		{!! $field->prepend !!}
		{!! $field->render() !!}
		{!! $field->append !!}

		@if($field->hasAddInputGroup())
		</div>
		@endif
		{!! $field->helpText !!}
	</div>
</div>