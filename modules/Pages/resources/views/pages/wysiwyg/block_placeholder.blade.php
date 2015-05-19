<div class="page-block-placeholder {{ ($name == -1) ? 'page-block-placeholder-hidden' : ''}}" data-name="{{ $name }}">
	<div class="page-block-placeholder-title pull-left">
		@if ($name == '0')
			@lang('widgets::core.label.hide')
		@else
			{{ $name }}
		@endif
	</div>

	<div class="page-block-placeholder-buttons pull-right">
		{!! link_to_route('backend.widget.popup_list', trans('widgets::core.button.add_to_page'), [$page->getId()], [
			'class' => 'btn btn-success btn-xs popup add-widget',
			'data-icon' => 'plus fa-fw', 'data-popup-type' => 'ajax'
		]) !!}
	</div>

	<div class="clearfix"></div>

	<div class="sortable page-block-placeholder-widgets">
		@foreach($widgets as $widget)
			@include('pages::pages.wysiwyg.widget_placeholder', [
				'widget' => $widget->getObject(),
			])
		@endforeach
	</div>
</div>