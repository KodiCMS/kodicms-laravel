$(function() {
	checkNotifications();
	setInterval(checkNotifications, 60000);

	Api.get('/api.updates.check.new_version', {}, function(response) {
		if(response.content && response.content.newVersion) {
			var row = response.content;
			CMS.Notifications.show(false, row.message, row.sent_at, row.title, row.icon, row.color);
		}
	});
});

function checkNotifications() {
	Api.get('/api.notifications.list', {}, function(response) {
		for(i in response.content) {
			var row = response.content[i];
			CMS.Notifications.create(row.id, row.message, row.sent_at, row.type.title, row.type.icon, row.type.color);
		}

		CMS.Notifications.fetchList();
	});
}