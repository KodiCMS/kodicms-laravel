@if($section)
<div class="mail-container-header">
	{!! UI::icon($section->getIcon()) !!} {{ $section->getName() }}

	<div class="pull-right">
		{!! link_to_route('backend.datasource.edit', '', $section->getId(), [
			'data-icon' => 'cog', 'class' => 'btn btn-sm btn-default'
		]) !!}
		{!! link_to_route('backend.datasource.remove', '', $section->getId(), [
			'data-icon' => 'trash-o', 'class' => 'btn btn-xs btn-danger'
		]) !!}
	</div>
</div>
@endif

<div class="mail-controls headline-actions">
	{!! $toolbar !!}
</div>

@if($headline)
<div class="mail-list headline no-margin">
	{!! $headline !!}
</div>
@endif