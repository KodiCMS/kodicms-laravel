<div class="panel">
	@if (count($widgets) > 0)
	@foreach ($widgets as $type => $_widgets)
	<div class="panel-heading">
		<span class="panel-title">{{ $type }}</span>
	</div>
	<div class="panel-body padding-sm">
		@foreach ($_widgets as $id => $widget)
		{!! Form::button($widget->name, [
			'icon' => 'tag',
			'data-id' => $id,
			'class' => 'popup-widget-item btn btn-default'
		]) !!}
		@endforeach
	</div>
	@endforeach
	@else
	<div class="panel-body">
		<h3>@lang('widgets::core.messages.all_widgets_placed')</h3>
	</div>
	@endif
</div>