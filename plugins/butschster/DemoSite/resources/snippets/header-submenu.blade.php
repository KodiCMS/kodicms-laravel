<ul class="dropdown-menu">
	@foreach ($pages as $page)
	<li @if($page['is_active']) class="active" @endif>
		{!! link_to($page['url'], $page['title']) !!}
	</li>
	@endforeach
</ul>