CMS.controllers.add(['role.get.edit', 'role.get.create'], function () {
	$('.panel').on('click', '.check_all', function(e) {
		var $inputs = $(this)
			.closest('table')
			.find('input');

		$inputs.each(function() {
			$(this).prop('checked', !$(this).prop('checked')).change();
		})

		e.preventDefault();
	});
});