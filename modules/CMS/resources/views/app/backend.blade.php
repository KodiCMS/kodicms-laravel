<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<meta name="generator" content="{{ CMS::NAME }} v. {{ CMS::VERSION }}">
		<meta name="author" content="ButscH" />
		<title>{{ $title or 'Backend' }} &ndash; {{ config('site.title') }}</title>
		<link href="{{ asset('favicon.ico') }}" rel="favourites icon" />
	</head>
	<body id="body.{{ $bodyId or 'backend' }}" class="{{ $requestType }} {{ $theme or 'default' }} main-menu-fixed">
		<div id="main-wrapper">
			@if($requestType != 'iframe')
			<header>
				@include('cms::app.blocks.navbar')
			</header>
			<div id="main-menu" role="navigation">
				@include('cms::app.blocks.navigation', ['breadcrumbs' => $breadcrumbs])
			</div>
			<div id="main-menu-bg"></div>
			@endif
			<div id="content-wrapper">
				@include('cms::app.blocks.breadcrumbs', ['breadcrumbs' => $breadcrumbs])

				<section id="content" >
				{!! $content or NULL !!}
				</section>

				@include('cms::app.blocks.footer')
			</div>
		</div>
	</body>
</html>