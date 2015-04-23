<div class="sort-pages">
	@foreach ($pages as $page)
	<ul class="dd-list list-unstyled">
		<li class="dd-item" data-id="{{ $page['id'] }}">
			<div class="dd-root">
				{!! UI::icon('folder-open') !!}
				{{ $page['title'] }}
			</div>
		</li>
	</ul>
	<div class="dd" id="nestable">
		@if (!empty($page['childs'])) {!! recurseSortPages($page['childs']) !!} @endif
	</div>
	@endforeach
</div>
<?php

function recurseSortPages(array $childs)
{
	$data = '';
	if (empty($childs))
		return $data;

	$data = '<ul class="dd-list list-unstyled">';
	foreach ($childs as $page) {
		$data .= (string) view('pages::pages.partials.reorderitem', [
			'page' => $page,
			'childs' => !empty($page['childs']) ? recurseSortPages($page['childs']) : ''
		]);
	}

	$data .= '</ul>';

	return $data;
}