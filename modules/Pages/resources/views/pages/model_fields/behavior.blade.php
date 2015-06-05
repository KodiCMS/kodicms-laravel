<div {!! HTML::attributes($group->getAttributes()) !!}>
	{!! $label->render(['class' => $group->labelCol ]) !!}

	<div class="{{ $group->fieldCol }}">
		<div id="behavor_options_container">
		{!! $field->render() !!}
			<div id="behavor_options"></div>
		</div>
	</div>
</div>