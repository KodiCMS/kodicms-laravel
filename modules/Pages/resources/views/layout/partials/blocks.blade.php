@if (count($blocks))
<span class="text-muted text-normal text-sm">
	<strong>@lang('pages::layout.label.blocks'):</strong> {{ implode(', ', $blocks) }}
</span>
@endif