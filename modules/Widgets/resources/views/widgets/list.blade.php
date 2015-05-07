<div class="panel">
	<div class="panel-heading">
		@if (acl_check('widgets.add'))
			{!! link_to_route('backend.widget.create', trans('widgets::core.button.create'), [], [
			'class' => 'btn btn-primary', 'data-icon' => 'plus', 'data-hotkeys' => 'ctrl+a'
			]) !!}
		@endif
	</div>

	<table class="table table-primary table-striped table-hover">
		<colgroup>
			<col width="250px" />
			<col width="150px" />
			<col />
			<col width="150px" />
			<col width="150px" />
			<col width="100px" />
		</colgroup>
		<thead>
		<tr>
			<th>@lang('widgets::core.field.name')</th>
			<th>@lang('widgets::core.field.type')</th>
			<th class="hidden-xs">@lang('widgets::core.field.description')</th>
			<th class="hidden-xs">@lang('widgets::core.field.template')</th>
			<th class="hidden-xs">@lang('widgets::core.field.cache')</th>
			<th class="text-right">@lang('widgets::core.field.actions')</th>
		</tr>
		</thead>
		<tbody>
		@foreach ($widgets as $widget)
			<tr class="widget">

			</tr>
		@endforeach
		</tbody>
	</table>
</div>

{!! $widgets->render() !!}