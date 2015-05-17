<div class="page-widget-placeholder" data-id="{{ $widget->getId() }}">
	<span class="drag-handle">☰</span>
	{{ $widget->getName() }}
	<div class="pull-right page-widget-placeholder-actions">
		<a href="{{ route('backend.widget.edit', [$widget->getId()]) }}" target="_parent">{!! UI::icon('wrench fa-fw'); !!}</a>
		<a href="#" class="widget-remove">{!! UI::icon('times'); !!}</a>
	</div>
</div>