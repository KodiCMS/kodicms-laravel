$(function() {
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});

	try {
		i18n.init({
			lng: LOCALE,
			fallbackLng: 'ru',
			useLocalStorage: true,
			interpolationPrefix: ':',
			interpolationSuffix: '',
			localStorageExpirationTime: 86400000, // in ms, default 1 week
			resGetPath: '/cms/js/locale/:lng.json'
		}, runApplication);
	} catch (err) {
		runApplication();
	}

	function runApplication() {
		CMS.ui.init();
		KodiCMS.start(null, CMS.settings);

		CMS.controllers.call();
		CMS.messages.init();
		CMS.Notifications.init();
	}
});