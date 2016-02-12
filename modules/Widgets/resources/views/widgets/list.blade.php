<div class="panel">
	<div class="panel-heading">
		@if (acl_check('widgets.add'))
		{!! link_to_route('backend.widget.create', trans('widgets::core.button.create'), [], [
			'class' => 'btn btn-primary btn-labeled', 'data-icon' => 'plus', 'data-hotkeys' => 'ctrl+a'
		]) !!}
		@endif
	</div>

	@if(count($widgets) > 0)
	<table class="table table-primary table-striped table-hover">
		<colgroup>
			<col width="250px" />
			<col width="150px" />
			<col />
			<col width="150px" />
			<col width="150px" />
			<col width="100px" />
		</colgroup>
		<thead>
		<tr>
			<th>@lang('widgets::core.field.name')</th>
			<th>@lang('widgets::core.field.type')</th>
			<th>@lang('widgets::core.field.description')</th>
			<th>@lang('widgets::core.field.template')</th>
			<th>@lang('widgets::core.field.cache')</th>
			<th class="text-right">@lang('widgets::core.field.actions')</th>
		</tr>
		</thead>
		<tbody>
		@foreach ($widgets as $widget)
			<tr class="widget @if($widget->isCorrupt()) bg-danger @endif" data-id="{{ $widget->id }}">
				@if($widget->isCorrupt())
				<th data-icon="lock">{{ $widget->name }}</th>
				<td class="type">
					{!! UI::label($widget->getType()) !!}
				</td>
				<td colspan="3">
					@lang('widgets::core.messages.corrupted')
				</td>
				<td class="actions text-right">
					@if (acl_check('widgets.delete'))
					{!! link_to_route('backend.widget.delete', '', [$widget], [
					'data-icon' => 'times fa-inverse', 'class' => 'btn btn-xs btn-confirm'
					]) !!}
					@endif
				</td>
				@else
				<th class="name" data-icon="cube">
					@if (acl_check('widgets.edit'))
					{!! link_to_route('backend.widget.edit', $widget->name, [$widget]) !!}
					@else
					{{ $widget->name }}
					@endif

					@if($widget->isHandler())
					{!! UI::label(trans('widgets::core.label.handler'), 'warning') !!}
					@endif
				</th>
				<td class="type">
					{!! link_to_route('backend.widget.list.by_type', UI::label($widget->getType()), [$widget->type]) !!}
				</td>
				<td class="description">
					<span class="text-muted">{{ $widget->description }}</span>

					@if($widget->isHandler())

					@if(!empty($widget->description))<br />@endif
					<span class="text-success text-xs">{!!
						trans('widgets::core.messages.is_handler', [
							'url' => $widget->getHandlerLink(),
							'route' => route('widget.handler', [$widget->id])
						])
					 !!}</span>
					@endif
				</td>
				<td class="template">
					@if ($widget->isRenderable())
					<span class="editable-template label label-info" data-value="{{ $widget->template or null }}">{{ $widget->template }}</span>
					@endif
				</td>
				<td class="cache">
				@if ($widget->isCacheable())
					@if ($widget->isCacheEnabled())
					{!! UI::label($widget->getCacheLifetime(), 'warning') !!}
					@else
					{!! UI::label(0, 'success') !!}
					@endif
				@endif
				</td>
				<td class="actions text-right">
					@if (acl_check('widgets.location') and !$widget->isHandler())
					{!! link_to_route('backend.widget.location', '', [$widget], [
						'data-icon' => 'sitemap', 'class' => 'btn btn-xs btn-primary popup'
					]) !!}
					@endif
					@if (acl_check('widgets.delete'))
					{!! Form::open(['route' => ['backend.widget.delete', $widget], 'style' => 'display: inline-block']) !!}
						{!! Form::button('', [
							'type' => 'submit',
							'data-icon' => 'times fa-inverse', 'class' => 'btn btn-xs btn-danger btn-confirm'
							])
						!!}
					{!! Form::close() !!}
					@endif
				</td>
				@endif
			</tr>
		@endforeach
		</tbody>
	</table>
	@else
	<div class="panel-body">
		<h3>@lang('widgets::core.messages.empty')</h3>
	</div>
	@endif
</div>

{!! $widgets->render() !!}