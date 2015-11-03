<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<meta name="generator" content="{{ CMS::NAME }} v.{{ CMS::VERSION }}">
		<meta name="author" content="ButscH" />
		<meta name="csrf-token" content="{{ csrf_token() }}" />
		<title>{{ $title or 'Backend' }} &ndash; {{ config('cms.title') }}</title>
		<link href="{{ asset('cms/favicon.ico') }}" rel="favourites icon" />
		@yield('head')

		{!! Assets::getGroup('global', 'templateScripts') !!}
		{!! Meta::render() !!}
		{!! Assets::getGroup('global', 'backendEvents') !!}
		@yield('scripts')
	</head>
	<body id="body.{{ $bodyId or 'backend' }}" class="{{ $requestType }} theme-{{ $theme or 'default' }} main-menu-fixed">
		<div id="main-wrapper">
			@if($requestType != 'iframe')
			<header>
				@include('cms::app.partials.navbar')
			</header>
			<div id="main-menu" role="navigation">
				@include('cms::app.partials.navigation', ['breadcrumbs' => $breadcrumbs])
			</div>
			<div id="main-menu-bg"></div>
			@endif
			<div id="content-wrapper">
				@if($requestType != 'iframe')
				@include('cms::app.partials.breadcrumbs', ['breadcrumbs' => $breadcrumbs])
				@endif

				<section id="content" >
				{!! $content or NULL !!}
				</section>

				@if($requestType != 'iframe')
				@include('cms::app.partials.footer')

				@if(config('app.profiling'))
				@include('cms::app.profiler')
				@endif

				@endif
			</div>
		</div>

		{!! Assets::getJsList(true) !!}
		@yield('footer_scripts')
	</body>
</html>