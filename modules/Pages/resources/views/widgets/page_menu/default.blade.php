<nav class="navbar navbar-default">
	@if($header)
	<div class="navbar-header">
		<a class="navbar-brand" href="{{ url()->current() }}">{{ $header }}</a>
	</div>
	@endif

	<div class="container-fluid">
		<ul class="nav navbar-nav">
			@foreach($pages as $page)
			@if(!empty($page['childs']))
			<li class="dropdown @if($page['is_active']) active @endif">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">{{ $page['title'] }} <span class="caret"></span></a>
				<ul class="dropdown-menu" role="menu">
					<li @if($page['is_active'])class="active"@endif>{!! HTML::link($page['url'], $page['title']) !!}</li>
					<li class="divider"></li>
					@foreach($page['childs'] as $child)
					<li @if($child['is_active'])class="active"@endif>{!! HTML::link($child['url'], $child['title']) !!}</li>
					@endforeach
				</ul>
			</li>
			@else
			<li @if($page['is_active']) class="active" @endif>{!! HTML::link($page['url'], $page['title']) !!}</li>
			@endif
			@endforeach
		</ul>
	</div>
</nav>
