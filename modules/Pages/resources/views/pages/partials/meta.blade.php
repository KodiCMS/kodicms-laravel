<div class="panel-body">
	@foreach ($page->getMetaFields() as $field)
	{!! $page->renderField($field) !!}
	@endforeach

	<hr class="panel-wide" />

	{!! $page->renderField('robots') !!}
</div>