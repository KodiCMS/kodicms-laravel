<h2>@lang('userguide::core.modules_have_docs')</h2>
<hr />
@if (!empty($modules))

@foreach ($modules as $url => $options)
<blockquote>
	<p><strong>{!! link_to_route('backend.userguide.docs', $options['name'], [$url]) !!}</strong></p>
	<small>{{ $options['description'] }}</small>
</blockquote>
@endforeach

@else
<div class="alert alert-block">
	<p>@lang('userguide::core.no_modules')</p>
</div>
@endif