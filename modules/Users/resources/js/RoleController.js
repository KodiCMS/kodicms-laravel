CMS.controllers.add(['role.get.edit', 'role.get.add'], function () {
	$('.panel').on('click', '.check_all', function(e) {
		var $list = $(this)
			.closest('table')
			.find('input')
			.check();

		e.preventDefault();
	});
});