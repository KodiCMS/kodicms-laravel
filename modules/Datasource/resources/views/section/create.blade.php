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
			<td class="text-right">@lang('datasource::core.field.type')</td>
		</tr>
		</thead>
		<tbody>
		@foreach($section->getSystemFields() as $field)
			@if($field instanceof \KodiCMS\Datasource\Contracts\FieldInterface)
			<tr>
				<td class="sys">
					{{ $field->getKey() }}
				</td>
				<td>
					{{ $field->getName() }}
				</td>
				<td class="text-right">
					{!! UI::label($field->getType()->getCategory(), 'success') !!} {!! UI::label($field->getTypeTitle()) !!}
				</td>
			</tr>
			@elseif($field instanceof \KodiCMS\Datasource\Contracts\FieldGroupInterface)
				@foreach($field->getFields() as $groupField)
				<tr>
					<td class="sys">
						{{ $groupField->getKey() }}
					</td>
					<td>
						{{ $groupField->getName() }}
					</td>
					<td class="text-right">
						{!! UI::label($groupField->getType()->getCategory(), 'success') !!} {!! UI::label($groupField->getTypeTitle()) !!}
					</td>
				</tr>
				@endforeach
			@endif
		@endforeach
		</tbody>
	</table>
	</div>
</div>

<div class="form-actions panel-footer">
	@include('cms::app.partials.actionButtons', ['route' => 'backend.datasource.list'])
</div>

{!! Form::close() !!}