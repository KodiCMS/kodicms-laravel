CMS.controllers.add(['cron.get.create', 'cron.get.edit'], function() {
	$('#selector').cron({
		initial: JOB.crontime,
		onChange: function() {
			$('#crontime').val($(this).cron("value"));
		}
	});
});