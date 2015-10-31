@event('view.page.edit.sidebar.before', [$page])

<?php
$layout_name = $page->getLayout();
$layout_link = '';

if ((acl_check('layout.edit') or acl_check('layout.view')) and ! empty($layout_name))
{
	$layout_link = link_to_route('backend.layout.edit', $layout_name, [$layout_name], [
		'class' => 'popup fancybox.iframe'
	]);
}
?>

<div class="panel-body">
	@if ($page->id != 1)
		{!! $page->renderField('parent_id') !!}
	@endif

	{!! $page->renderField('layout_file') !!}

	<hr class="panel-wide" />

	{!! $page->renderField('behavior') !!}

	<hr class="panel-wide" />

	@if ($page->id != 1)
	{!! $page->renderField('status') !!}
	<hr class="panel-wide" />
	@endif

	@if ($page->id != 1)
	{!! $page->renderField('published_at') !!}
	@endif
</div>

@event('view.page.edit.sidebar.after', [$page])