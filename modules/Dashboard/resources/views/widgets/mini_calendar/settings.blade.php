<div class="panel-body">
	<div class="form-group">
		<label class="control-label col-xs-3">@lang('dashboard::types.mini_calendar.label.format')</label>
		<div class="col-xs-9">
			{!! Form::select('settings[format]', $formats, $widget->format, ['class' => 'form-control']) !!}
		</div>
	</div>
</div>