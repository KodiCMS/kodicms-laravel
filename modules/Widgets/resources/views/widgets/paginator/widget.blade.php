<div class="panel-heading" data-icon="list">
	<span class="panel-title">@lang('widgets::types.paginator.label.settings')</span>
</div>

<div class="panel-body ">
	<div class="form-group form-inline">
		<label class="control-label col-md-3" for="list_size">@lang('widgets::types.paginator.setting.list_size')</label>
		<div class="col-md-9">
			{!! Form::text('settings[list_size]', $widget->list_size, [
			'class' => 'form-control', 'id' => 'list_size', 'size' => 3
			]) !!}
		</div>
	</div>
	<div class="form-group form-inline">
		<label class="control-label col-md-3" for="list_offset">@lang('widgets::types.paginator.setting.list_offset')</label>
		<div class="col-md-9">
			{!! Form::text('settings[list_offset]', $widget->list_offset, [
			'class' => 'form-control', 'id' => 'list_offset', 'size' => 3
			]) !!}
		</div>
	</div>
</div>