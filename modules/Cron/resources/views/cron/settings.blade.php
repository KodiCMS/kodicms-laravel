<div class="panel-heading panel-toggler" data-icon="bolt">
	<span class="panel-title">@lang('cron::core.settings.title')</span>
</div>
<div class="note note-warning">
	{!! UI::icon('lightbulb-o fa-lg') !!} @lang('cron::core.settings.info')
	<br /><br />
	<strong>* * * * * php {{ base_path('artisan') }} cms:cron:run &gt; /dev/null 2&gt;&amp;1</strong>
</div>
<div class="panel-body panel-spoiler">
	<div class="form-group">
		<label class="control-label col-sm-3">@lang('cron::core.settings.agent')</label>
		<div class="col-sm-3">
			{!! Form::select('config[job][agent]', $agents, (int) config('job.agent'), ['class' => 'form-control']) !!}
		</div>
	</div>
</div>