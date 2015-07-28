<div class="panel-heading">
	<span class="panel-title" data-icon="th-list">Fields</span>
</div>
<table id="section-fields" class="table table-striped">
	<colgroup>
		<col width="30px" />
		<col width="100px" />
		<col width="150px" />
		<col />
		<col width="100px" />
	</colgroup>
	<tbody>
	@foreach($fields as $field)

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
		<td>
			<?php
			if (!empty($types = $field->getWidgetTypes()))
			{
				$widgets = DatasourceManager::getWidgetsBySection($types, $field->getRelatedSectionId());

				if (isset($widgets[$widget->id]))
				{
					unset($widgets[$widget->id]);
				}

				if (!empty($widgets))
				{
					$widgetsList = [];

					foreach($widgets as $id => $_widget)
					{
						$widgetsList[$id] = $_widget->getName();
					}
					$widgets = [trans('cms::core.label.not_set')] + $widgetsList;

					$selected = NULL;

					if (isset($widget->field_widget[$field->getId()]))
					{
						$selected = $widget->field_widget[$field->getId()];
					}

					echo Form::select("settings[field_widget][{$field->getId()}]", $widgets, $selected);
				}
			}
			?>
		</td>
		<td class="text-center">
			{!! link_to_route('backend.datasource.field.location', '', [$field->getId()], ['data-icon' => 'sitemap', 'class' => 'btn btn-xs btn-default']) !!}
		</td>
	</tr>
	@endforeach
	</tbody>
</table>