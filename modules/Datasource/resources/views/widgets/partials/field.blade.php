<tr id="field-{{ $field->getDBKey() }}" class="field-{{ $field->type }}">
	<td class="f">
		{!! Form::checkbox("settings[selected_fields][{$field->getDBKey()}]", $field->getDBKey(), in_array($field->getDBKey(), $widget->getSelectedFields()), [
			'id' => "field-{$field->getDBKey()}-checkbox"
		]) !!}
	</td>
	<td class="sys">
		{!! Form::label("field-{$field->getDBKey()}-checkbox", $field->getDBKey()) !!}
	</td>
	<td>
		{!! link_to_route('backend.datasource.field.edit', $field->getName(), [$field->getId()]) !!}
	</td>
	<td></td>
	<td class="text-center">
		<?php /*
		{!! link_to_route('backend.datasource.field.location', '', [$field->getId()], ['data-icon' => 'sitemap', 'class' => 'btn btn-xs btn-default']) !!}
		*/ ?>
	</td>
</tr>