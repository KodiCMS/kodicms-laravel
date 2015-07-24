<div class="mail-container-header">
	{!! UI::icon($section->getIcon()) !!} {{ $section->getName() }}

	<div class="btn-group pull-right">
		{!! link_to_route('backend.datasource.edit', '', $section->getId(), [
			'data-icon' => 'cog', 'class' => 'btn btn-sm btn-default'
		]) !!}
	</div>
</div>

@if (isset($toolbar))
<div class="mail-controls headline-actions">
	{!! $toolbar !!}
</div>
@endif

<div class="mail-list headline no-margin">
	{!! $headline !!}
</div>