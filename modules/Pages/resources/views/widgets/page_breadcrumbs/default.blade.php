@if ($header)
<h3>{{ $header }}</h3>
@endif

@if (count($breadcrumbs) > 1)
<ul class="breadcrumb">
@foreach ($breadcrumbs as $crumb)
	@if ($breadcrumbs->isLast())
	<li class="active">{{ $crumb->getName() }}</li>
	@else
	<li>{!! $crumb->getLink() !!}</li>
	@endif
@endforeach
</ul>
@endif