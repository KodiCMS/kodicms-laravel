$(function() {
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});

	CMS.ui.init();
	KodiCMS.start(null, CMS.settings);

	CMS.controllers.call();

	setTimeout(function() {
		CMS.notifications.init();
	}, 1500);

	CMS.messages.init();
});