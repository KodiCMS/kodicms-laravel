<div class="panel-heading">
	<span class="panel-title" data-icon="th-list">@lang('datasource::core.field.list')</span>
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
	{!! $field->renderWidgetFieldTemplate($widget) !!}
	@endforeach
	</tbody>
</table>