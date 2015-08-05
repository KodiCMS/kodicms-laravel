@if(!empty($name))
<div class="panel-heading">
	<span class="panel-title">{{ $group->getName() }}</span>
</div>
@endif

<div class="panel-body">
	@foreach ($fields as $field)
	{!! $field->renderDocumentTemplate($document) !!}
	@endforeach
</div>