<div class="panel-heading">
	{!! $fields->getByKey('header')->renderDocumentTemplate($document) !!}
	{!! $fields->getByKey('published')->renderDocumentTemplate($document) !!}
</div>

<div class="panel-toggler text-center panel-heading" data-target-spoiler=".spoiler-meta" data-icon="chevron-down panel-toggler-icon">
	<span class="muted">Metadata</span>
</div>
<div class="panel-spoiler spoiler-meta panel-body">
	@foreach ($fields->getOnly('meta_title', 'meta_keywords', 'meta_description', 'created_by_id') as $field)
		{!! $field->renderDocumentTemplate($document) !!}
	@endforeach
	<hr class="panel-wide" />
</div>

<div class="panel-body">
	@foreach ($fields->getOnly('description', 'text') as $field)
	{!! $field->renderDocumentTemplate($document) !!}
	@endforeach
</div>

<div class="form-actions panel-footer">
	@include('cms::app.partials.actionButtons', ['route' => ['backend.datasource.list', $section->getId()]])
</div>