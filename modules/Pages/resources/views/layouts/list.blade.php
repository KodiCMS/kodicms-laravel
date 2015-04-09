<div class="panel">
	@if (!$collection->isReadOnly())
	<div class="panel-heading">
		@if (acl_check('layout.add'))
		{!! link_to_route('backend.layout.create', trans('pages::layout.button.add'), [], [
			'class' => 'btn btn-default', 'data-icon' => 'plus', 'data-hotkeys' => 'ctrl+a'
		]) !!}
		@endif

		@if (acl_check('layout.rebuild'))
		{!! Form::button(trans('pages::layout.button.rebuild'), [
			'data-icon' => 'refresh',
			'class' => 'btn btn-inverse btn-xs',
			'data-api-url' => '/api.layout.rebuild',
			'data-api-method' => 'POST'
		]) !!}
		@endif
	</div>
	@else
	<div class="alert alert-danger alert-dark no-margin-b">
		@lang('pages::layout.message.directory_not_writeable', [':dir' => $collection->getRealPath()])
	</div>
	@endif

	<table class="table-primary table table-striped table-hover">
		<colgroup>
			<col />
			<col width="150px" />
			<col width="100px"/>
			<col width="100px" />
			<col width="100px" />
		</colgroup>
		<thead>
		<tr>
			<th>@lang('pages::layout.field.name')</th>
			<th class="hidden-xs">@lang('pages::layout.field.modified')</th>
			<th>@lang('pages::layout.field.size')</th>
			<th class="hidden-xs">@lang('pages::layout.field.path')</th>
			<th class="text-right">@lang('pages::layout.field.actions')</th>
		</tr>
		</thead>
		<tbody>
		<?php foreach ($collection as $layout): ?>
		<tr id="layout_{{ $layout->getName() }}">
			<th class="name">
				{!! UI::icon('desktop') !!}
				@if ($layout->isReadOnly())
				<span class="label label-warning">@lang('pages::layout.label.readonly')</span>
				@endif

				@if (acl_check('layout.edit') OR acl_check('layout.view'))
				{!! link_to_route('backend.layout.edit', $layout->getName(), [$layout->getName()], [
					'class' => $layout->isReadOnly() ? 'popup fancybox.iframe' : ''
				]) !!}
				@else
				@endif

				<?php  /*if (count($layout->blocks()) > 0): ?>
				<span class="text-muted text-normal text-sm">
						<?php echo __('Layout blocks'); ?>: <span class="layout-block-list"><?php echo implode(', ', $layout->blocks()); ?></span>
					</span>
				<?php endif; */ ?>
			</th>
			<td class="modified hidden-xs">
				{{ $layout->getMTime() }}
			</td>
			<td class="size">
				{{ $layout->getSize() }}
			</td>
			<td class="direction hidden-xs">
				{!! UI::label($layout->getRelativePath()) !!}
			</td>
			<td class="actions text-right">
				@if (acl_check('layout.delete'))
				{!! link_to_route('backend.layout.delete', '', [$layout->getName()], [
					'data-icon' => 'times fa-inverse',
					'class' => 'btn btn-danger btn-xs btn-confirm'
				]) !!}
				@endif
			</td>
		</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
</div>