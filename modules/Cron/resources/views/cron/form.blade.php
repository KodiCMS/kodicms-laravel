{!! Form::model($job, [
	'route' => [$action, $job],
	'class' => 'form-horizontal panel'
]) !!}
<div class="panel-heading">
	<span class="panel-title">@lang('cron::core.tab.general')</span>

	<div class="panel-heading-controls">
		@if ($job->exists && acl_check('cron.run'))
			{!! link_to_route('backend.cron.run', trans('cron::core.button.run'), [$job], [
				'data-icon' => 'bolt', 'class' => 'btn btn-danger btn-sm btn-labeled'
			]) !!}
		@endif
	</div>
</div>
<div class="panel-body">
	<div class="form-group form-group-lg">
		<label class="control-label col-md-3" for="name">@lang('cron::core.field.name')</label>
		<div class="col-md-9">
			{!! Form::text('name', NULL, [
				'class' => 'form-control', 'id' => 'name'
			]) !!}
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-md-3">@lang('cron::core.field.task_name')</label>
		<div class="col-md-9">
			{!! Form::select('task_name', $job->getTypes(), null, ['class' => 'form-control']) !!}
		</div>
	</div>
</div>

<div class="panel-heading" data-icon="clock-o">
	<span class="panel-title">@lang('cron::core.tab.options')</span>
</div>
<div class="panel-body">
	<div class='well form-inline'>
		<label for="date_start">@lang('cron::core.field.date_start')</label>
		{!! Form::text('date_start', is_null($job->date_start) ? new Carbon\Carbon : $job->date_start, ['class' => 'datetimepicker form-control', 'id' => 'date_start']) !!}
		&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;
		<label for="date_end">@lang('cron::core.field.date_end')</label>
		{!! Form::text('date_end', is_null($job->date_end) ? with(new Carbon\Carbon)->addYears(10) : $job->date_end, ['class' => 'datetimepicker form-control', 'id' => 'date_end']) !!}
	</div>
	<div class="form-group">
		<label class="control-label col-md-3" for="interval">@lang('cron::core.field.interval')</label>
		<div class="col-md-9 form-inline">
			{!! Form::text('interval', null, ['class' => 'form-control col-sm-auto', 'id' => 'interval']) !!}

			<span class="flags">
				<span class="label" data-value="{{ Date::MINUTE }}">@lang('cron::core.interval.minute')</span>
				<span class="label" data-value="{{ Date::HOUR }}">@lang('cron::core.interval.hour')</span>
				<span class="label" data-value="{{ Date::DAY }}">@lang('cron::core.interval.day')</span>
				<span class="label" data-value="{{ Date::WEEK }}">@lang('cron::core.interval.week')</span>
				<span class="label" data-value="{{ Date::MONTH }}">@lang('cron::core.interval.month')</span>
				<span class="label" data-value="{{ Date::YEAR }}">@lang('cron::core.interval.year')</span>
			</span>
		</div>
	</div>
	<div class="form-group">
		<div class="col-md-offset-3 col-md-9">
			@lang('cron::core.interval.or')
		</div>
	</div>
	<div class="form-group">
		<label class="control-label col-md-3" for="crontime">@lang('cron::core.field.crontime')</label>
		<div class="col-md-9 form-inline">

			<div id="selector" class="well well-sm"></div>

			{!! Form::text('crontime', null, ['class' => 'form-control', 'id' => 'crontime']) !!}
			<span class="help-inline">{!! link_to('http://ru.wikipedia.org/wiki/Cron', trans('cron::core.crontab.help'), ['target' => '_blank']) !!}</span>

			<pre style="font-size: 10px; background: none; border: none;">
* * * * *
| | | | --- @lang('cron::core.crontab.weekday')

| | | ----- @lang('cron::core.crontab.month')

| | ------- @lang('cron::core.crontab.day')

| --------- @lang('cron::core.crontab.hour')

----------- @lang('cron::core.crontab.minute')
			</pre>
		</div>
	</div>
</div>

<div class="form-actions panel-footer">
	@include('cms::app.partials.actionButtons', ['route' => 'backend.cron.list'])
</div>
{!! Form::close() !!}
@include('cron::cron.logs', ['job' => $job])