<div class="page-block-placeholder {{ ($name == -1) ? 'page-block-placeholder-hidden' : ''}}" data-name="{{ $name }}">
	<div class="page-block-placeholder-title pull-left">
		@if ($name == '0')
			@lang('widgets::core.label.hide')
		@else
			{{ $name }}
		@endif
	</div>

	<div class="page-block-placeholder-buttons pull-right">
		{!! Form::button(trans('widgets::core.button.add_to_page'), [
			'class' => 'btn btn-success btn-xs fancybox.ajax popup add-widget',
			'data-icon' => 'plus fa-fw',
			'href' => route('backend.widget.popup_list', [$page->id])
		]) !!}
	</div>

	<div class="clearfix"></div>

	<div class="sortable">
		@foreach($widgets as $widget)
			@include('pages::pages.wysiwyg.widget_placeholder', [
				'widget' => $widget->getObject(),
			])
		@endforeach
	</div>
</div>