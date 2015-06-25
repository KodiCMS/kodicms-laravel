<div {!! HTML::attributes($group->getAttributes()) !!}>
	<div class="{{ $group->fieldCol }}">
		<div class="checkbox-inline">
			{!! $field->render() !!} {!! $label->render() !!}
		</div>
	</div>
</div>