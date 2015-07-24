<div class="panel-body">
	@foreach ($fields as $field)
		{!! $field->renderBackendTemplate($document) !!}
	@endforeach
</div>

<div class="form-actions panel-footer">
	@include('cms::app.partials.actionButtons', ['route' => ['backend.datasource.list', $section->getId()]])
</div>