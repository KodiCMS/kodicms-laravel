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
	{!! Assets::getGroup('global', 'frontendEvents') !!}
	@yield('scripts')
</head>
<body id="body.{{ $bodyId or 'backend' }}" class="{{ $theme or 'theme-default' }}">
	{!! $content or NULL !!}

	{!! Assets::getJsList(true) !!}
	@yield('footer_scripts')
</body>
</html>