@if(!is_null($paginator) and $paginator->hasPages())
<nav>
	<ul class="pagination">
	@if($paginator->previousPageUrl())
		<li>
			<a href="{{ $paginator->previousPageUrl(1) }}" aria-label="Previous">
				<span aria-hidden="true">&laquo;</span>
			</a>
		</li>
	@else
		<li class="disabled"><a href="#" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>
	@endif
	@foreach($paginator->getUrlRange(1, $paginator->lastPage()) as $i => $url)
		@if($i == $paginator->currentPage())
			<li class="active"><a href="#">{{ $i }}</a></li>
		@else
			<li><a href="{{ $url }}">{{ $i }}</a></li>
		@endif
	@endforeach
	@if($paginator->nextPageUrl())
		<li>
			<a href="{{ $paginator->nextPageUrl() }}" aria-label="Next">
				<span aria-hidden="true">&raquo;</span>
			</a>
		</li>
	@else
		<li class="disabled"><a href="#" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>
	@endif
	</ul>
</nav>
@endif