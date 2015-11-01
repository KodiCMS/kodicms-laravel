<div class="panel">
	@if (!$collection->isReadOnly())
	<div class="panel-heading">
		@if (acl_check('layout.add'))
		{!! link_to_route('backend.layout.create', trans('pages::layout.button.add'), [], [
			'class' => 'btn btn-default btn-labeled', 'data-icon' => 'plus', 'data-hotkeys' => 'ctrl+a'
		]) !!}
		@endif

		@if ($collection->getTotal() > 0 and acl_check('layout.rebuild'))
		<div class="panel-heading-controls">
			{!! Form::button(trans('pages::layout.button.rebuild'), [
			'data-icon' => 'refresh',
			'class' => 'btn btn-info btn-sm btn-labeled',
			'data-api-url' => '/api.layout.rebuild',
			'data-preloader' => '#layoutList'
			]) !!}
		</div>
		@endif
	</div>
	@else
	<div class="alert alert-danger alert-dark no-margin-b">
		@lang('pages::layout.messages.directory_not_writeable', ['dir' => $collection->getRealPath()])
	</div>
	@endif

	@if($collection->getTotal() > 0)
	<table class="table-primary table table-striped table-hover" id="layoutList">
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

				@if (acl_check('layout.edit') or acl_check('layout.view'))
				{!! link_to_route('backend.layout.edit', $layout->getName(), [$layout->getName()], [
					'class' => $layout->isReadOnly() ? 'popup' : ''
				]) !!}
				@else
				@endif

				<span class="layout-block-list">{!! view('pages::layout.partials.blocks', ['blocks' => $layout->getBlocks()]) !!}</span>
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
				{!! Form::open(['route' => ['backend.layout.delete', $layout->getName()]]) !!}
					{!! Form::button('', [
						'type' => 'submit',
						'data-icon' => 'times fa-inverse', 'class' => 'btn btn-xs btn-danger btn-confirm'
					]) !!}
				{!! Form::close() !!}
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