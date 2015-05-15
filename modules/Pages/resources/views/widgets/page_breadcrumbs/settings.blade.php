<div class="panel-heading">
	<span class="panel-title">@lang('pages::widgets.page_breadcrumbs.label.excluded_pages')</span>
</div>
<table class="table table-noborder table-striped">
	<colgroup>
		<col width="50px" />
		<col />
	</colgroup>
	<thead>
	<tr>
		<th></th>
		<th></th>
	</tr>
	</thead>
	<tbody>
	@foreach($pageSitemap->flatten() as $page)
	<tr>
		@if($page['id'] > 1)
		<td class="text-right">
			{!! Form::checkbox('settings[excluded_pages][]', $page['id'], in_array($page['id'], $widget->excluded_pages), ['id' => 'page'.$page['id']]) !!}
		</td>
		<th>
			<label for="page{{ $page['id'] }}">{{ str_repeat('&nbsp;', $page['level'] * 10) }} {{ $page['title'] }} <span class="text-muted">[{{ $page['uri'] }}]</span></label>
		</th>
		@else
		<td></td>
		<th>{{ $page['title'] }}</th>
		@endif
	</tr>
	@endforeach
	</tbody>
</table>