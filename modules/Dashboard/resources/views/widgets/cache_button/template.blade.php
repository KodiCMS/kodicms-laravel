<div class="panel dashboard-widget panel-warning panel-body-colorful panel-dark" data-id="{{ $widget->getId() }}">
	<button type="button" class="close remove_widget">{!! UI::icon('times') !!}</button>
	<div class="panel-body text-center handle">
		{!! Form::button(__('Clear cache'), [
				'data-icon' => 'trash-o fa-lg',
				'class' => 'btn btn-lg btn-success btn-flat btn-block',
				'data-api-url' => '/api.cache.clear',
				'data-method' => 'DELETE'
		]) !!}
	</div>
</div>