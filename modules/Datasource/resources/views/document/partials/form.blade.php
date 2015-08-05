@foreach ($fields->getGroupedFields() as $group)
	{!! $group->renderDocumentTemplate($document) !!}
@endforeach

<div class="form-actions panel-footer">
	@include('cms::app.partials.actionButtons', ['route' => ['backend.datasource.list', $section->getId()]])
</div>