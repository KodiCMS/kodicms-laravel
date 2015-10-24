@include('plugins::pluginItem')

<div id="pluginsMap" class="panel">
	<table class="table table-primary table-striped table-hover" id="PluginsList">
		<colgroup>
			<col />
			@if (acl_check('plugins.change_status'))
			<col width="100px" />
			@endif
		</colgroup>
		<thead>
		<tr>
			<th>@lang('plugins::core.field.title')</th>
			@if (acl_check('plugins.change_status'))
			<th>@lang('plugins::core.field.actions')</th>
			@endif
		</tr>
		</thead>
		<tbody></tbody>
	</table>
</div>