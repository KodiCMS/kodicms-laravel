<div class="page-widget-placeholder" data-id="{{ $widget->getId() }}">
	<span class="drag-handle">☰</span>
	{{ $widget->getName() }}
	<div class="pull-right">
		<a href="{{ route('backend.widget.edit', [$widget->getId()]) }}" target="_parent"><i class="fa fa-wrench"></i></a>
		<a href="#" class="widget-remove"><i class="fa fa-times"></i></a>
	</div>
</div>