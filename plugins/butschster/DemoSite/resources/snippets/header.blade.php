<?php $logo = array_get($parameters, 'inversed') ? 'logo-color.png' : 'logo.png'; ?>

<div class="navbar @if(array_get($parameters, 'inversed')) navbar-inverse @endif ">
	<div class="container">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
				<i class="fa fa-align-justify"></i>
			</button>
			{!! link_to('/', HTML::image(resources_url("/images/$logo"), 'KodiCMS'), [
				'class' => 'navbar-brand'
			]) !!}
		</div>
		<div class="navbar-collapse collapse">
			<ul class="nav navbar-nav">
				<li @if (Request::is('/')) class="active" @endif >
					{!! link_to('/', 'Home') !!}
				</li>

				@foreach($pages as $page)
				@if(empty($page['childs']))
				<li @if($page['is_active']): ?> class="active" @endif>
					{!! link_to($page['url'], $page['title']) !!}
				</li>
				@else
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">
						{{ $page['title'] }}
						<b class="caret"></b>
					</a>

					@snippet('header-submenu.blade', ['pages' => $page['childs']])
				</li>
				@endif
				@endforeach
			</ul>
		</div>
	</div>
</div>