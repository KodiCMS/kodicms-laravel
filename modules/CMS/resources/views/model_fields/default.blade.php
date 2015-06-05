<div {!! HTML::attributes($group->getAttributes()) !!}>
	{!! $label->render(['class' => $group->labelCol ]) !!}

	<div class="{{ $group->fieldCol }}">
		{!! $field->render() !!}
	</div>
</div>