{!! Form::model($section, [
	'route' => ['backend.datasource.create.post', $typeObject->getType()],
	'class' => 'form-horizontal panel'
]) !!}

@include('datasource::section.partials.information', ['section' => $section])

<div class="panel-body">
	<div class="panel">
	<div class="panel-heading" data-icon="th-list fa-lg">
		<span class="panel-title">@lang('datasource::core.field.list')</span>
	</div>
	<table id="section-fields" class="table table-primary table-striped table-hover">
		<colgroup>
			<col width="100px" />
			<col width="200px" />
			<col />
		</colgroup>
		<thead>
		<tr>
			<td>@lang('datasource::core.field.key')</td>
			<td>@lang('datasource::core.field.name')</td>
			<td>@lang('datasource::core.field.type')</td>
		</tr>
		</thead>
		<tbody>
		@foreach($section->getSystemFields() as $field)
			<tr data-id="{{ $field->getId() }}">
				<td class="sys">
					<label for="{{ $field->getKey() }}">{{ $field->getKey() }}</label>
				</td>
				<td>
					{{ $field->getName() }}
				</td>
				<td>
					{!! UI::label($field->getTypeTitle()) !!}
				</td>
			</tr>
		@endforeach
		</tbody>
	</table>
	</div>
</div>

<div class="form-actions panel-footer">
	@include('cms::app.partials.actionButtons', ['route' => 'backend.datasource.list'])
</div>

{!! Form::close() !!}