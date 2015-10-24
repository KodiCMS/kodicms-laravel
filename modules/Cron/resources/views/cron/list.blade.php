<div class="panel">
	<div class="panel-heading">
		@if (acl_check('cron.create'))
		{!! link_to_route('backend.cron.create', trans('cron::core.button.create'), [], [
			'class' => 'btn btn-primary btn-labeled', 'data-icon' => 'plus', 'data-hotkeys' => 'ctrl+a'
		]) !!}
		@endif
	</div>

	@if(count($jobs))
	<table class="table table-primary table-striped table-hover">
		<colgroup>
			<col width="250px" />
			<col width="200px" />
			<col />
			<col width="150px" />
			<col width="100px" />
		</colgroup>
		<thead>
		<tr>
			<th>@lang('cron::core.field.name')</th>
			<th class="hidden-xs">@lang('cron::core.field.task_name')</th>
			<th>@lang('cron::core.field.status')</th>
			<th class="hidden-xs"><nobr>@lang('cron::core.field.last_run')</nobr></th>
			<th class="hidden-xs"><nobr>@lang('cron::core.field.next_run')</nobr></th>
			<th class="text-right">@lang('cron::core.field.actions')</th>
		</tr>
		</thead>
		<tbody>
		@foreach ($jobs as $job)
			<tr class="item">
				<td class="job-name">
					@if (acl_check('cron.edit'))
						{!! link_to_route('backend.cron.edit', $job->name, [$job]) !!}
					@else
						{!! UI::icon('lock') !!} {{ $job->name }}
					@endif
				</td>
				<td class="job-function hidden-xs">
					{!! UI::label($job->task_name) !!}
				</td>
				<td class="job-status">
					{{ $job->statusString }}
				</td>
				<td class="job-last-run hidden-xs">
					{{ Date::format($job->last_run) }}
				</td>
				<td class="job-interval hidden-xs">
					{{ Date::format($job->next_run) }}
				</td>
				<td class="actions text-right">
				@if (acl_check('cron.delete'))
				{!! Form::open(['route' => ['backend.cron.delete', $job]]) !!}
					{!! Form::button('', [
						'type' => 'submit',
						'data-icon' => 'times fa-inverse', 'class' => 'btn btn-xs btn-danger btn-confirm'
					]) !!}
				{!! Form::close() !!}
				@endif
				</td>
			</tr>
		@endforeach
		</tbody>
	</table>
	@else
	<div class="panel-body">
		<h3>@lang('cron::core.messages.empty')</h3>
	</div>
	@endif
</div>

{!! $jobs->render() !!}