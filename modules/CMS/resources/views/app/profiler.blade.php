<style type="text/css">
	.kodicms-profiler { background: #fff; margin-top: 20px;border: 1px solid #ccc; padding: 5px; }
	.kodicms-profiler table.profiler { width: 100%; margin: 0 auto; border-collapse: collapse;  }
	.kodicms-profiler table.profiler th,
	.kodicms-profiler table.profiler td { padding: 0 0.4em; background: #fff; border: solid 1px #999; border-width: 1px 0; text-align: left; font-weight: normal; font-size: 1em; color: #111; vertical-align: top; text-align: right; }
	.kodicms-profiler table.profiler th.name { text-align: left; }
	.kodicms-profiler table.profiler tr.group th { background: #222; color: #eee; border-color: #222; }
	.kodicms-profiler table.profiler tr.group td { background: #222; color: #777; border-color: #222; }
	.kodicms-profiler table.profiler tr.group td.time { padding-bottom: 0; }
	.kodicms-profiler table.profiler tr.headers th { text-transform: lowercase; font-variant: small-caps; background: #ddd; color: #777; }
	.kodicms-profiler table.profiler tr.mark th.name { width: 40%; background: #fff; vertical-align: middle; }
	.kodicms-profiler table.profiler tr.mark td { padding: 0; }
	.kodicms-profiler table.profiler tr.mark.final td { padding: 0 0.4em; }
	.kodicms-profiler table.profiler tr.mark td > div { position: relative; padding: 0.2em 0.4em; }
	.kodicms-profiler table.profiler tr.mark td div.value { position: relative; z-index: 2; }
	.kodicms-profiler table.profiler tr.mark td div.graph { position: absolute; top: 0; bottom: 0; right: 0; left: 100%; background: #71bdf0; z-index: 1; }
	.kodicms-profiler table.profiler tr.mark.memory td div.graph { background: #acd4f0; }
	.kodicms-profiler table.profiler tr.mark td.current { background: #eddecc; }
	.kodicms-profiler table.profiler tr.mark td.min { background: #d2f1cb; }
	.kodicms-profiler table.profiler tr.mark td.max { background: #ead3cb; }
	.kodicms-profiler table.profiler tr.mark td.average { background: #ddd; }
	.kodicms-profiler table.profiler tr.mark td.total { background: #d0e3f0; }
	.kodicms-profiler table.profiler tr.time td { border-bottom: 0; font-weight: bold; }
	.kodicms-profiler table.profiler tr.memory td { border-top: 0; }
	.kodicms-profiler table.profiler tr.final th.name { background: #222; color: #fff; }
	.kodicms-profiler table.profiler abbr { border: 0; color: #777; font-weight: normal; }
	.kodicms-profiler table.profiler:hover tr.group td { color: #ccc; }
	.kodicms-profiler table.profiler:hover tr.mark td div.graph { background: #1197f0; }
	.kodicms-profiler table.profiler:hover tr.mark.memory td div.graph { background: #7cc1f0; }
</style>

<?php
$group_stats      = Profiler::groupStats();
$group_cols       = array('min', 'max', 'average', 'total');
$application_cols = array('min', 'max', 'average', 'current');
?>

<div class="kodicms-profiler">
	@foreach (Profiler::groups() as $group => $benchmarks)
	<table class="profiler">
		<tr class="group">
			<th class="name" rowspan="2">{{ ucfirst($group) }}</th>
			<td class="time" colspan="4">{{ number_format($group_stats[$group]['total']['time'], 6) }} <abbr title="seconds">s</abbr></td>
		</tr>
		<tr class="group">
			<td class="memory" colspan="4">{{ number_format($group_stats[$group]['total']['memory'] / 1024, 4) }} <abbr title="kilobyte">kB</abbr></td>
		</tr>
		<tr class="headers">
			<th class="name">Benchmark</th>
			@foreach ($group_cols as $key)
			<th class="{{ $key }}">{{ ucfirst($key) }}</th>
			@endforeach
		</tr>
		@foreach ($benchmarks as $name => $tokens)
		<tr class="mark time">
			<?php $stats = Profiler::stats($tokens) ?>

			<th class="name" rowspan="2" scope="rowgroup">{{ $name }} ({{ count($tokens) }})</th>
			@foreach ($group_cols as $key)
			<td class="{{ $key }}">
				<div>
					<div class="value">{{ number_format($stats[$key]['time'], 6) }} <abbr title="seconds">s</abbr></div>
					@if ($key === 'total')
					<div class="graph" style="left: <?php echo $group_stats[$group]['max']['time'] ? max(0, 100 - $stats[$key]['time'] / $group_stats[$group]['max']['time'] * 100) : '0' ?>%"></div>
					@endif
				</div>
			</td>
			@endforeach
		</tr>
		<tr class="mark memory">
			@foreach ($group_cols as $key)
			<td class="{{ $key }}">
				<div>
					<div class="value">{{ number_format($stats[$key]['memory'] / 1024, 4) }} <abbr title="kilobyte">kB</abbr></div>
					@if ($key === 'total')
					<div class="graph" style="left: <?php echo $group_stats[$group]['max']['memory'] ? max(0, 100 - $stats[$key]['memory'] / $group_stats[$group]['max']['memory'] * 100) : '0' ?>%"></div>
					@endif
				</div>
			</td>
			@endforeach
		</tr>
		@endforeach
	</table>
	@endforeach

	<table class="profiler">
		<?php $stats = Profiler::application() ?>
		<tr class="final mark time">
			<th class="name" rowspan="2" scope="rowgroup">Application Execution ({{ $stats['count'] }})</th>
			@foreach ($application_cols as $key)
			<td class="{{ $key }}">{{ number_format($stats[$key]['time'], 6) }} <abbr title="seconds">s</abbr></td>
			@endforeach
		</tr>
		<tr class="final mark memory">
			@foreach ($application_cols as $key)
			<td class="{{ $key }}">{{ number_format($stats[$key]['memory'] / 1024, 4) }} <abbr title="kilobyte">kB</abbr></td>
			@endforeach
		</tr>
	</table>
</div>
