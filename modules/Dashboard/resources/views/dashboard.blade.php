<div class="page-header">
	<div class="row">
		<div class="col-xs-6">
			<h1 data-icon="dashboard">@lang('dashboard::core.title.dashboard')</h1>
		</div>

		@if(acl_check('backend.dashboard.manage'))
		<div class="col-xs-6 text-right">
			{!! Form::checkbox('draggable', 1, 0, [
				'class' => 'form-switcher', 'data-size' => 'mini', 'id' => 'cache',
				'data-on' => trans('dashboard::core.buttons.draggable.enabled'),
				'data-off' => trans('dashboard::core.buttons.draggable.disabled'),
				'data-onstyle' => 'success'
			]) !!}

			<a class="btn btn-primary btn-labeled popup" data-popup-type="href" href="{{ route('api.dashboard.widget.list') }}" id="add-widget">
				<span class="btn-label icon fa fa-cubes"></span>@lang('dashboard::core.buttons.add_widget')
			</a>
		</div>
		@endif
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