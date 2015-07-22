<div class="panel">
	<div class="panel-heading" data-icon="th-list fa-lg">
		<span class="panel-title">Fields</span>
		<div class="panel-heading-controls">
			{!! Form::button('Remove fields', [
				'data-icon' => 'trash-o',
				'class' => 'btn btn-sm btn-danger btn-labeled',
				'id' => 'remove-fields'
			]) !!}
		</div>
	</div>
	<table id="section-fields" class="table table-primary table-striped table-hover">
		<colgroup>
			<col width="30px" />
			<col width="50px" />
			<col width="100px" />
			<col width="200px" />
			<col width="100px" />
			<col />
		</colgroup>
		<thead>
		<tr>
			<td></td>
			<td>Position</td>
			<td>Key</td>
			<td>Header</td>
			<td>Type</td>
			<td>Show in headline</td>
		</tr>
		</thead>
		<tbody>
		@foreach ($fields as $field)
		<tr data-id="{{ $field->getId() }}">
			<td class="f">
				@if(!$field->isSystem())
				{!! Form::checkbox('remove_field[]', $field->getId(), false, ['id' => $field->getKey()]) !!}
				@endif
			</td>
			<td class="position"><span class="editable-position">{{ $field->getPosition() }}</span></td>
			<td class="sys">
				<label for="{{ $field->getKey() }}">{{ $field->getKey() }}</label>
			</td>
			<td>
				{!! link_to_route('backend.datasource.field.edit', $field->getName(), [$field->getId()]) !!}
			</td>
			<td>
				{!! UI::label($field->getTypeTitle()) !!}
			</td>
			<td>
				{!! Form::checkbox("in_headline[{$field->getId()}]", 1, false) !!}
			</td>
		</tr>
		@endforeach
		</tbody>
	</table>

	<div class="panel-footer">
		<div class="btn-group">
			{!! link_to_route('backend.datasource.field.create', 'Create field', $section->getId(), [
				'data-icon' => 'plus', 'class' => 'btn btn-primary btn-labeled'
			]) !!}
		</div>
	</div>
</div>