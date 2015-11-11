<?php
use KodiCMS\Support\Helpers\Profiler;

$group_stats = Profiler::groupStats();
$group_cols = ['min', 'max', 'average', 'total'];
$application_cols = ['min', 'max', 'average', 'current'];
?>

<div id="profiler">
    <div class="">
        @foreach (Profiler::groups() as $group => $benchmarks)
            <table class="profiler">
                <thead data-toggle="collapse" href="#collapse_{{ snake_case($group) }}">
                <tr class="group">
                    <th class="name" rowspan="2">{{ ucfirst($group) }}</th>
                    <td class="time" colspan="4">{{ number_format($group_stats[$group]['total']['time'], 6) }} <abbr title="seconds">s</abbr></td>
                </tr>
                <tr class="group">
                    <td class="memory" colspan="4">{{ number_format($group_stats[$group]['total']['memory'] / 1024, 4) }} <abbr title="kilobyte">kB</abbr></td>
                </tr>
                </thead>
                <tbody class="collapse" id="collapse_{{ snake_case($group) }}">
                <tr class="headers">
                    <th class="name">@lang('cms::profiler.benchmark')</th>
                    @foreach ($group_cols as $key)
                        <th class="{{ $key }}">@lang("cms::profiler.{$key}")</th>
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
                </tbody>
            </table>
        @endforeach
        <table class="profiler">
            <thead>
            <?php $stats = Profiler::application() ?>
            <tr class="final mark time">
                <th class="name" rowspan="2" scope="rowgroup">@lang('cms::profiler.application_execution') ({{ $stats['count'] }})</th>
                @foreach ($application_cols as $key)
                    <td class="{{ $key }}">{{ number_format($stats[$key]['time'], 6) }} <abbr title="seconds">s</abbr></td>
                @endforeach
            </tr>
            <tr class="final mark memory">
                @foreach ($application_cols as $key)
                    <td class="{{ $key }}">{{ number_format($stats[$key]['memory'] / 1024, 4) }} <abbr title="kilobyte">kB</abbr></td>
                @endforeach
            </tr>
            </thead>
        </table>
    </div>
</div>
