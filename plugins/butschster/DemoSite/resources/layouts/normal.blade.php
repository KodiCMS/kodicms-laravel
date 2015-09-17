<!DOCTYPE html>
<html lang="{{ Lang::getLocale() }}">
<head>
	{!!
		Meta::addPackage(['holder', 'fontawesome', 'demo-assets'], true)
			->addMeta(['name' => 'author', 'content' => 'KodiCMS'])
			->setFavicon(resources_url('/favicon.ico'))
			->build()
	!!}
</head>
<body>
	@block('header', ['inversed' => Request::is('/')])

	@block('breadcrumbs')

	@block('body-top')

	<div class="content container">
		@block('body')
	</div>

	@block('body-bottom')
	@block('footer')
</body>
</html>