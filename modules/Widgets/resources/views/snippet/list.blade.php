<div class="panel">
	@if (!$collection->isReadOnly())
		<div class="panel-heading">
			@if (acl_check('snippet.add'))
				{!! link_to_route('backend.snippet.create', trans('widgets::snippet.button.add'), [], [
				'class' => 'btn btn-default btn-labeled', 'data-icon' => 'plus', 'data-hotkeys' => 'ctrl+a'
				]) !!}
			@endif
		</div>
	@else
		<div class="alert alert-danger alert-dark no-margin-b">
			@lang('widgets::snippet.messages.directory_not_writeable', ['dir' => $collection->getRealPath()])
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
			<th>@lang('widgets::snippet.field.name')</th>
			<th class="hidden-xs">@lang('widgets::snippet.field.modified')</th>
			<th>@lang('widgets::snippet.field.size')</th>
			<th class="hidden-xs">@lang('widgets::snippet.field.path')</th>
			<th class="text-right">@lang('widgets::snippet.field.actions')</th>
		</tr>
		</thead>
		<tbody>
		@foreach ($collection as $snippet)
		<tr id="snippet_{{ $snippet->getKey() }}">
			<th class="name">
				{!! UI::icon('desktop') !!}
				@if ($snippet->isReadOnly())
					<span class="label label-warning">@lang('widgets::snippet.label.readonly')</span>
				@endif

				@if (acl_check('snippet.edit') or acl_check('snippet.view'))
				{!! link_to_route('backend.snippet.edit', $snippet->getName(), [$snippet->getName()], [
					'class' => $snippet->isReadOnly() ? 'popup' : ''
				]) !!}
				@else
				@endif
			</th>
			<td class="modified hidden-xs">
				{{ $snippet->getMTime() }}
			</td>
			<td class="size">
				{{ $snippet->getSize() }}
			</td>
			<td class="direction hidden-xs">
				{!! UI::label($snippet->getRelativePath()) !!}
			</td>
			<td class="actions text-right">
				@if (acl_check('snippet.delete'))
				{!! Form::open(['route' => ['backend.snippet.delete', $snippet->getName()]]) !!}
					{!! Form::button('', [
						'type' => 'submit',
						'data-icon' => 'times fa-inverse', 'class' => 'btn btn-xs btn-danger btn-confirm'
						])
					!!}
				{!! Form::close() !!}
				@endif
			</td>
		</tr>
		@endforeach
		</tbody>
	</table>
	@else
	<div class="panel-body">
		<h3>@lang('widgets::snippet.messages.empty')</h3>
	</div>
	@endif
</div>