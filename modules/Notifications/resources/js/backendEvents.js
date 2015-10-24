$(function() {
	checkNotifications();
	setInterval(checkNotifications, 60000);

	Api.get('/api.updates.check.new_version', {}, function(response) {
		if(response.content && response.content.newVersion) {
			CMS.Notifications.show(response.content);
		}
	});
});

function checkNotifications() {
	Api.get('/api.notifications.list', {}, function(response) {
		for(i in response.content) {
			CMS.Notifications.create(response.content[i]);
		}

		CMS.Notifications.fetchList();
	});
}