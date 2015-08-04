<div class="panel-heading">
	@if(!empty($name))
	<div class="panel-title">{{ $group->getName() }}</div>
	@endif

	@foreach ($fields as $field)
	{!! $field->renderDocumentTemplate($document) !!}
	@endforeach
</div>
