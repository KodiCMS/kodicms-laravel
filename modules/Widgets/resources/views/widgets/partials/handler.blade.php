<div class="alert alert-danger note-dark no-margin-b">
	{!! trans('widgets::core.messages.is_handler', [
	'url' => $widget->getHandlerLink(),
	'route' => route('widget.handler', [$widget->id])
	]) !!}
</div>