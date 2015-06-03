CMS.controllers.add('user.get.profile', function () {
	var toolbar = $('.profile-toolbar');
	var toolbar_l = toolbar.text().replace(/\t/g, '').replace(/\n/g, '').replace(/&nbsp;/g, '').replace(/ /g, '').length;
	
	if(!toolbar_l) toolbar.css({'padding': 0});
});

CMS.controllers.add('user.get.edit', function () {
	$('#themes .theme').on('click', function (e) {
		if ($(this).hasClass('active'))
			e.preventDefault();

		$('#themes .active').removeClass('active');
		$(this).addClass('active');

		activateTheme($(this).data('theme'));
		e.preventDefault();
	});
});


var activateTheme = function(theme) {
	Api.post('/api.user.meta', {key: 'cms_theme', value:theme, uid: USER.id});
	document.body.className = document.body.className.replace(/theme\-[a-z0-9\-\_]+/ig, 'theme-' + theme);
}