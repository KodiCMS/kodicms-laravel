<div class="panel-heading">
	<div class="form-group form-group-lg">
		<label for="name" class="col-sm-2 control-label">@lang('pages::layout.field.name')</label>
		<div class="col-sm-10">
			<div class="input-group">
				{!! Form::text('name', NULL, [
				'class' => 'form-control slugify', 'id' => 'name', 'data-separator' => "."
				]) !!}
				<span class="input-group-addon">{{ $layout->getExt() }}</span>
			</div>
		</div>
	</div>
</div>
@if (!$layout->isReadOnly() or $layout->isNew())
<div class="panel-toggler text-center panel-heading" data-target-spoiler=".spoiler-settings">
	{!! UI::icon('chevron-down panel-toggler-icon') !!} <span class="muted">@lang('pages::layout.label.settings')</span>
</div>
<div class="panel-spoiler spoiler-settings panel-body">
	<div class="form-group">
		<label class="col-md-3 control-label">@lang('pages::layout.label.roles')</label>
		<div class="col-md-9">
			{!! Form::select('roles[]', $roles, null, [
				'class' => 'form-control', 'multiple'
			]) !!}
		</div>
	</div>

	<hr class="panel-wide" />
</div>
@endif
<div class="panel-heading">
	<span class="panel-title">@lang('pages::layout.field.content')</span>
	{!! UI::badge($layout->getRelativePath()) !!}

	@if (count($layout->getBlocks()) > 0)
		<span class="text-muted text-normal text-sm">
			@lang('pages::layout.label.blocks'): <span class="layout-block-list">
				<?php echo implode(', ', $layout->getBlocks()); ?>
			</span>
		</span>
	@endif

	@if (!$layout->isReadOnly() or $layout->isNew())
		<div class="panel-heading-controls">
			{!! Form::button(trans('pages::layout.button.filemanager'), [
			'data-icon' => 'folder-open',
			'data-el' => 'textarea_content',
			'class' => 'btn btn-filemanager btn-labeled btn-info btn-sm'
			]) !!}
		</div>
	@endif
</div>

{!! Form::textarea('content', NULL, [
'class' => 'form-control',
'id' => 'textarea_content',
'data-height' => 600,
'data-readonly' => (!$layout->isEditable() and !$layout->isCreatable()) ? 'true' : 'false'
]) !!}

@if(!$layout->isEditable() and !$layout->isCreatable())
	<div class="panel-default alert alert-danger alert-dark no-margin-b">
		@lang('pages::layout.messages.layout_not_writeable')
	</div>
@elseif (acl_check('layout.edit'))
	<div class="form-actions panel-footer">
		@include('cms::app.partials.actionButtons', ['route' => 'backend.layout.list'])
	</div>
@endif