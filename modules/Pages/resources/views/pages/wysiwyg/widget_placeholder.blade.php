<div class="page-widget-placeholder" data-id="{{ $widget->getId() }}">
	<div class="page-widget-placeholder-heading">
		<span class="drag-handle">{!! UI::icon('arrows fa-fw'); !!}</span>
		{{ $widget->getName() }} <span class="text-muted">[{{ $widget->getTypeTitle() }}]</span>

		<div class="page-widget-placeholder-actions">
			<a href="{{ route('backend.widget.edit', [$widget->getId()]) }}" class="popup" data-popup-parent="true">
				{!! UI::icon('wrench fa-fw'); !!}
			</a>
			<a href="#" class="widget-remove">{!! UI::icon('times'); !!}</a>
		</div>
	</div>

	{!! (new \KodiCMS\Widgets\Engine\WidgetRenderHTML($widget))->render() !!}
</div>