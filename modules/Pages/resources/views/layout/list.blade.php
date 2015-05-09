<div class="panel">
	@if (!$collection->isReadOnly())
	<div class="panel-heading">
		@if (acl_check('layout.add'))
		{!! link_to_route('backend.layout.create', trans('pages::layout.button.add'), [], [
			'class' => 'btn btn-default', 'data-icon' => 'plus', 'data-hotkeys' => 'ctrl+a'
		]) !!}
		@endif

		@if ($collection->getTotal() > 0 and acl_check('layout.rebuild'))
		{!! Form::button(trans('pages::layout.button.rebuild'), [
			'data-icon' => 'refresh',
			'class' => 'btn btn-inverse btn-xs',
			'data-api-url' => '/api.layout.rebuild'
		]) !!}
		@endif
	</div>
	@else
	<div class="alert alert-danger alert-dark no-margin-b">
		@lang('pages::layout.messages.directory_not_writeable', ['dir' => $collection->getRealPath()])
	</div>
	@endif

	@if($collection->getTotal() > 0)
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
		@foreach ($collection as $layout)
		<tr id="layout_{{ $layout->getKey() }}">
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

				@if (count($layout->getBlocks()) > 0)
				<span class="text-muted text-normal text-sm">
					@lang('pages::layout.label.blocks'): <span class="layout-block-list">
						<?php echo implode(', ', $layout->getBlocks()); ?>
					</span>
				</span>
				@endif
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
		@endforeach
		</tbody>
	</table>
	@else
	<div class="panel-body">
		<h3>@lang('pages::layout.messages.empty')</h3>
	</div>
	@endif
</div>