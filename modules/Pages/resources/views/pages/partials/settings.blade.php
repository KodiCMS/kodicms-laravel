@event('view.page.edit.sidebar.before', [$page])

<?php
$layout_name = $page->getLayout();
$layout_link = '';

if ((acl_check('layout.edit') OR acl_check('layout.view')) AND ! empty($layout_name))
{
	$layout_link = link_to_route('backend.layout.edit', $layout_name, [$layout_name], [
		'class' => 'popup fancybox.iframe'
	]);
}
?>

<div class="panel-body">
	@if ($page->id != 1)
	<div class="form-group">
		<label class="control-label col-md-3" for="parent_id">
			@lang('pages::core.field.parent_id')
		</label>
		<div class="col-md-6">
			{!! Form::select('parent_id', $pagesMap, NULL, ['class' => 'form-control']) !!}
		</div>
	</div>
	@endif

	<div class="form-group">
		<label class="control-label col-md-3" for="layout_file">
			@lang('pages::core.field.layout_file')
		</label>
		<div class="col-md-6">
			{!! Form::select('layout_file', $page->getLayoutList(), NULL, ['class' => 'form-control']) !!}
		</div>

		<div class="col-md-3">
			@if (!empty($layout_name))
			{!! UI::label(trans('pages::core.label.page.current_layout', ['name' => $layout_link])) !!}
			@else
			{!! UI::label(trans('pages::core.label.page.layout_not_set'), 'danger') !!}
			@endif
		</div>

	</div>

	<hr class="panel-wide" />

	<div class="form-group">
		<label class="control-label col-md-3" for="behavior">
			@lang('pages::core.field.behavior')
		</label>
		<div class="col-md-6">
			{!! Form::select('behavior', $behaviorList, NULL, ['class' => 'form-control']) !!}
			<div id="behavor_options"></div>
		</div>
	</div>

	<hr class="panel-wide" />

	@if ($page->id != 1)
	<div class="form-group page-statuses">
		<label class="control-label col-md-3" for="status">
			@lang('pages::core.field.status')
		</label>
		<div class="col-md-6">
			{!! Form::select('status', $page->getStatusList(), NULL, ['class' => 'form-control']) !!}
		</div>
	</div>
	<hr class="panel-wide" />
	@endif

	@if ($page->id != 1)
	<div class="form-group">
		<label class="control-label col-md-3" for="published_at">
			@lang('pages::core.field.published_at')
		</label>
		<div class="col-md-6">
			{!! Form::text('published_at', NULL, ['class' => 'form-control datetimepicker']) !!}
		</div>
	</div>
	<hr class="panel-wide" />
	@endif
</div>

@event('view.page.edit.sidebar.after', [$page])