<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<meta name="generator" content="{{ CMS::NAME }} v.{{ CMS::VERSION }}">
	<meta name="author" content="ButscH" />
	<title>{{ $title or 'Backend' }} &ndash; {{ config('cms.title') }}</title>
	<link href="{{ asset('cms/favicon.ico') }}" rel="favourites icon" />

	{!! Assets::group('global', 'templateScripts') !!}
	{!! Assets::css() !!}
	{!! Assets::js() !!}
	{!! Assets::group('global', 'events') !!}

</head>
<body id="body.{{ $bodyId or 'backend' }}" class="{{ $theme or 'theme-default' }}">
		{!! $content or NULL !!}
</body>
</html>