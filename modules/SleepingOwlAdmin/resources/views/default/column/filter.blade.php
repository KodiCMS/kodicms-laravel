@if ( ! is_null($value))
	<a href="{{ $url }}">
		<i class="fa {{ $isSelf ? 'fa-filter' : 'fa-arrow-circle-o-right' }}" data-toggle="tooltip" title="{{ $isSelf ? trans('sleepingowladmin::core.table.filter') : trans('sleepingowladmin::core.table.filter-goto') }}"></i></a>
@endif