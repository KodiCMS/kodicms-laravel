<div class="page-block-placeholder {{ ($name == -1) ? 'page-block-placeholder-hidden' : ''}}" data-name="{{ $name }}">
	<strong>
		@if ($name == 'PRE')
			@lang('widgets::core.label.before_page_render')
		@elseif ($name == 'POST')
			@lang('widgets::core.label.after_page_render')
		@elseif ($name == '0')
			@lang('widgets::core.label.hide')
		@else
			{{ $name }}
		@endif
	</strong>
	<div class="sortable">
		@foreach($widgets as $widget)
			@include('pages::pages.wysiwyg.widget_placeholder', [
				'widget' => $widget->getObject(),
			])
		@endforeach
	</div>
	<div class="page-block-placeholder-buttons">
		<button class="btn btn-warning fancybox.ajax popup add-widget" data-icon="plus" href="{{ route('backend.widget.popup_list', [$page->id]) }}">@lang('widgets::core.button.add_to_page')</button>
	</div>
</div>