@section('page-content')
	@parent

<div class="panel-heading panel-toggler" data-hotkeys="shift+w">
	<span class="panel-title" data-icon="cubes">@lang('widgets::core.title.list')</h4>
</div>
<div class="panel-body panel-spoiler">
	@if (is_null($page->id))
	<h4>@lang('widgets::core.title.copy_widgets')</h4>
	<select name="widgets[from_page_id]" class="col-md-12">
		<option value="">@lang('widgets::core.label.dont_copy_widgets')</option>
		@foreach ($pages as $p)
		<option value="<{{ $p['id'] }}" {{ $p['id'] == $page->parent_id ? ' selected="selected"': '' }} > {{ str_repeat('- ', $p['level'] * 2) }}{{ $p['title'] }}</option>
		@endforeach
	</select>
	@else

	@if (acl_check('widgets.location'))

	<a class="btn btn-success fancybox.ajax popup" href="{{ route('backend.widget.popup_list', [$page->id]) }}" id="addWidgetToPage" data-icon="plus">
		@lang('widgets::core.button.add_to_page')
	</a>

	@if (acl_check('layout.rebuild'))
		{!! Form::button(trans('pages::layout.button.rebuild'), [
			'data-icon' => 'refresh',
			'class' => 'btn btn-inverse btn-xs',
			'data-api-url' => '/api.layout.rebuild'
		]) !!}
	@endif

	<br /><br />
	@endif
	<table class="table table-hover" id="widget-list">
		<colgroup>
			<col />
			<col width="100px" />
			<col width="280px" />
		</colgroup>
		<tbody>
		@foreach ($widgetsCollection as $widget)
		@include('widgets::widgets.page.row', ['widget' => $widget->getObject(), 'block' => $widget->getBlock(), 'position' => $widget->getPosition(), 'page' => $page])
		@endforeach
		</tbody>
	</table>
	@endif
</div>
@stop

@section('scripts')
<script src="/backend/cms/js/WidgetController.js"></script>
@stop