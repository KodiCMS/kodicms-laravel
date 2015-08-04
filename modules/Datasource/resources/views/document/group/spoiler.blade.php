<div class="panel-toggler text-center panel-heading" data-target-spoiler=".spoiler-{{ $group->getUniqueId() }}" data-icon="chevron-down panel-toggler-icon">
	<span class="muted">{{ $name }}</span>
</div>
<div class="panel-spoiler spoiler-{{ $group->getUniqueId() }} panel-body">
	@foreach ($fields as $field)
	{!! $field->renderDocumentTemplate($document) !!}
	@endforeach
	<hr class="panel-wide" />
</div>