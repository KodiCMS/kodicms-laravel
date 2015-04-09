{!! Form::open([
	'route' => 'backend.layout.edit.post',
	'class' => 'form-horizontal panel'
]) !!}

<div class="panel-heading">
	<div class="form-group form-group-lg">
		<label for="name" class="col-sm-2 control-label">@lang('pages::layout.field.name')</label>
		<div class="col-sm-10">
			<div class="input-group">
				{!! Form::text('name', $layout->getName(), [
				'class' => 'form-control sluggify', 'id' => 'name'
				]) !!}
				<span class="input-group-addon">{{ $layout->getExt() }}</span>
			</div>
		</div>
	</div>
</div>
<div class="panel-heading">
	<span class="panel-title">@lang('pages::layout.field.content')</span>
	{!! UI::badge($layout->getRelativePath()) !!}

	@if (!$layout->isReadOnly() OR $layout->isNew())
	<div class="panel-heading-controls">
		{!! Form::button(trans('pages::layout.button.filemanager'), [
			'data-icon' => 'folder-open',
			'data-el' => 'textarea_content',
			'class' => 'btn btn-filemanager btn-flat btn-info btn-sm'
		]) !!}
	</div>
	@endif
</div>
{!! Form::textarea('content', $layout->getContent(), [
	'class' => 'form-control',
	'id' => 'textarea_content',
	'data-height' => 600,
	'data-readonly' => ($layout->isReadOnly() OR ($layout->isNew() AND !$layout->isReadOnly())) ? 'off' : 'on'
]) !!}

@if(!$layout->isNew() AND $layout->isReadOnly())
<div class="panel-default alert alert-danger alert-dark no-margin-b">
	<?php echo __('File is not writable'); ?>
</div>
@elseif (acl_check('layout.edit'))
<div class="form-actions panel-footer">
	@include('cms::app.blocks.actionButtons', ['route' => 'backend.layout.list'])
</div>
@endif
{!! Form::close() !!}