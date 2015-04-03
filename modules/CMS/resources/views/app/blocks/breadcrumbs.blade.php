@if ($breadcrumbs->count() > 1)
<ul class="breadcrumb breadcrumb-page">
	@foreach($breadcrumbs as $breadcrumb)
	<li>
		@if(!$breadcrumbs->isLast())
		{!! $breadcrumb->getLink() !!}
		@else
		<span>{!! $breadcrumb->getName() !!}</span>
		<?php endif; ?>
	</li>
	@endforeach
</ul>
@endif