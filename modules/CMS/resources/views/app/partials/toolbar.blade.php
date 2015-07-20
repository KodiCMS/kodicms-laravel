@if(config('app.profiling'))
	@include('cms::app.profiler')
@endif