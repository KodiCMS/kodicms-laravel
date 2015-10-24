@section('scripts')
	@parent
	<script type="text/javascript">
		$(function() {
			var $pageId = $('#select_page_id');

			$pageId.on('change', function() {
				show_field($(this));
			});
			show_field($pageId);
		})

		function show_field($select) {
			var $cont = $('#page_level_container');
			($select.val() == 0) ? $cont .show() : $cont.hide();
		}
	</script>
@stop

<div class="panel-body">
	<div class="form-group">
		<label class="control-label col-md-3">@lang('pages::widgets.page_menu.setting.start_page')</label>
		<div class="col-md-4">
			{!! Form::select('settings[page_id]', $select, $widget->page_id, ['id' => 'select_page_id', 'class' => 'form-control']) !!}
		</div>
	</div>

	<div class="form-group form-inline" id="page_level_container">
		<label class="control-label col-md-3" for="page_level">@lang('pages::widgets.page_menu.setting.page_level')</label>
		<div class="col-md-9">
			{!! Form::text('settings[page_level]', $widget->page_level, [
				'id' => 'page_level', 'class' => 'form-control',
				'size' => 4
			]) !!}
		</div>
	</div>

	<div class="form-group">
		<div class="col-md-offset-3 col-md-9">
			<div class="checkbox">
				<label>{!! Form::checkbox('settings[include_children]', 1, $widget->include_children == 1) !!} @lang('pages::widgets.page_menu.setting.include_children')</label>
			</div>

			<div class="checkbox">
				<label>{!! Form::checkbox('settings[include_hidden]', 1, $widget->include_hidden == 1) !!} @lang('pages::widgets.page_menu.setting.include_hidden_pages')</label>
			</div>
		</div>
	</div>
</div>
<div class="panel-heading">
	<span class="panel-title">@lang('pages::widgets.page_menu.label.excluded_pages')</span>
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
		</td>
	</tr>
	@endforeach
	</tbody>
</table>