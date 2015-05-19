<div class="page-header">
	<div class="row">
		<div class="col-xs-6">
			<h1 data-icon="dashboard">@lang('dashboard::core.title.dashboard')</h1>
		</div>

		<div class="col-xs-6 text-right">
			<a class="btn btn-primary btn-labeled popup" data-popup-type="href" href="{{ route('api.dashboard.widget.list') }}" id="add-widget">
				<span class="btn-label icon fa fa-cubes"></span>@lang('dashboard::core.buttons.add_widget')
			</a>
		</div>
	</div>
</div>

<div id="dashboard-widgets">
	<div class="gridster">
		<ul class="list-unstyled">
			@foreach ($widgets as $data)
			<li
				@foreach ($data as $key => $v)
				<?php if($key == 'widget') continue; ?>
					data-{{ $key }}="{{ $v }}" @endforeach
			>
				<?php echo (new \KodiCMS\Dashboard\WidgetRenderDashboardHTML($data['widget']))->render(); ?>
			</li>
			@endforeach
		</ul>
	</div>
</div>