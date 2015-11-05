<form action="{{ $action }}" method="POST">
	<input type="hidden" name="_token" value="{{ csrf_token() }}" />
	<input type="hidden" name="_redirectBack" value="{{ $backUrl }}" />
	@foreach ($items as $item)
		{!! $item !!}
	@endforeach
	<div class="form-group">
		<input type="submit" value="{{ trans('sleepingowladmin::core.table.save') }}" class="btn btn-primary"/>
		<a href="{{ $backUrl }}" class="btn btn-default">{{ trans('sleepingowladmin::core.table.cancel') }}</a>
	</div>
</form>