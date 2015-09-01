@if ($header)
<h3>{{ $header }}</h3>
<hr />
@endif

<div class="tags_cloud text-center">
	@foreach ($tags as $tag => $params)
		{!! link_to(URL::current() . \KodiCMS\Support\Helpers\URL::query(['tag' => $tag]), $tag, [
			'style' => "font-size: {$params['size']}px; color: {$params['color']}"
		]) !!}
	@endforeach
</div>