<div class="panel dashboard-widget panel-dark panel-info">
	<div class="panel-heading">
		<span class="panel-title" data-icon="bar-chart">@lang('cms::profiler.title')</span>
		<div class="panel-heading-controls">
			<button type="button" class="btn btn-default btn-xs remove_widget">{!! UI::icon('times') !!}</button>
		</div>
	</div>
	<div class="stat-panel">
		<div class="stat-row">
			<div class="stat-cell bg-dark-gray padding-sm text-xs text-semibold">
				{!! UI::icon('dot-circle-o') !!}&nbsp;&nbsp;@lang('cms::profiler.application_execution') ({{ $stats['count'] }})
			</div>
		</div>
		<div class="stat-row">
			<div class="stat-counters">
				@foreach ($application_cols as $key)
				<div class="stat-cell bg-dark-gray padding-sm text-xs text-semibold">@lang("cms::profiler.{$key}")</div>
				@endforeach
			</div>
		</div>
		<div class="stat-row">
			<div class="stat-counters bordered no-border-t text-center">
				@foreach ($application_cols as $key)
				<div class="stat-cell col-xs-4 padding-sm no-padding-hr <?php echo $key ?>">
					<span class="text-bg">{{ number_format($stats[$key]['time'], 3) }} <abbr title="seconds">s</abbr></span><br>
				</div>
				@endforeach
			</div>
		</div>
		<div class="stat-row">
			<div class="stat-counters bordered no-border-t text-center">
				@foreach ($application_cols as $key)
				<div class="stat-cell col-xs-4 padding-sm no-padding-hr <?php echo $key ?>">
					<span class="text-bg">{{ number_format($stats[$key]['memory'] / 1024, 2) }} <abbr title="kilobyte">kB</abbr></span><br>
				</div>
				@endforeach
			</div>
		</div>

	</div>
</div>