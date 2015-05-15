@if ($header)
<h3>{{ $header }}</h3>
<hr />
@endif

<div class="media-list">
	@forelse($pages as $page)
	<div class="media">
		<div class="media-body">
			<h4 class="media-heading"><a href="{{ $page->getUrl() }}">{{ $page->getTitle() }}</a></h4>
			@part($page, 'body')
		</div>
	</div>
	@empty
	<h3>Empty list</h3>
	@endforelse
</div>