<div class="panel-body">
	<div class="form-group">
		<label class="control-label col-md-3">@lang('widgets::types.paginator.setting.linked_widget_id')</label>
		<div class="col-md-4">
			{!! Form::select('settings[linked_widget_id]', $select, $widget->linked_widget_id, [
				'id' => 'linked_widget_id', 'class' => 'form-control'
			]) !!}
		</div>
	</div>

	<hr class="panel-wide"/>

	<div class="form-group">
		<label class="control-label col-md-3" for="query_key">@lang('widgets::types.paginator.setting.query_key')</label>
		<div class="col-md-2">
			{!! Form::text('settings[query_key]',  $widget->query_key, [
				'id' => 'query_key', 'class' => 'form-control'
			]) !!}
		</div>
	</div>
</div>