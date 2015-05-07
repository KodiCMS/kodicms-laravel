@if (count($job->logs) > 0)
	<div class="panel no-margin-b">
		<div class="panel-heading">
			<span class="panel-title">@lang('cron::core.logs.title')</span>
		</div>
		<table class="table table-primary table-striped table-hover">
			<colgroup>
				<col width="200px" />
				<col />
			</colgroup>
			<thead>
				<tr>
					<th>@lang('cron::core.logs.created_at')</th>
					<th>@lang('cron::core.logs.status')</th>
				</tr>
			</thead>
			<tbody>
				@foreach ($job->logs as $log)
					<tr class="item">
						<td class="log-run-time">
							{{ Date::format($log->created_at) }}
						</td>
						<td class="job-status">
							{{ $log->statusString }}
						</td>
					</tr>
				@endforeach
			</tbody>
		</table>
	</div>
@endif