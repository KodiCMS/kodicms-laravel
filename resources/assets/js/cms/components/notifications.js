CMS.Notifications = {
	list: {},
	showed: [],
	container: '#notifications-container',
	init: function () {
		this.getContainer().slimScroll({
			height: 250
		});

		this.fetchList();
	},
	create: function (id, message, sent_at, type, icon, color) {
		var isset = this.list.length && _.find(this.list, function (row) {
			return row.id == id;
		});

		!isset && this.list.push({
			id: id,
			message: message,
			date: moment(sent_at),
			type: type || '',
			icon: icon,
			color: color || 'default'
		});
	},
	show: function (id, message, sent_at, type, icon, color) {
		var $cont = this.getContainer(),
			row;

		if (typeof id == 'object') {
			row = id;
		} else {
			row = {
				id: id,
				message: message,
				date: moment(sent_at),
				type: type || '',
				icon: icon,
				color: color || 'default'
			}
		}

		if(this.notificationIsShowed(row.id)) return;

		this.fetchNotification(row).prependTo($cont);
		this.showed.push(row.id);

		this.updateCounter();
	},
	read: function(id) {
		var self = this;
		Api.delete('/api.notification.read', {id: id}, function() {
			self.deleteNotifiction(id);
		});
	},
	updateCounter: function () {
		$('.counter', this.container).text(this.getTotal());
	},
	getTotal: function () {
		return this.showed.length;
	},
	getContainer: function () {
		return $('.notifications-list', this.container);
	},
	fetchList: function () {
		this.list = _.sortBy(this.list, function (row) {
			return !row.date.unix();
		});

		for (i in this.list) {
			this.show(this.list[i]);
		}
	},
	deleteNotifiction: function(id) {
		$('.notification[data-id="' + id + '"]', this.container).remove();

		this.list = _.filter(this.list, function(row) {
			return row.id != id;
		});

		this.showed = _.filter(this.showed, function(_id) {
			return _id == id;
		});

		this.updateCounter();
	},
	fetchNotification: function(row) {
		var $notification = $('<div class="notification" />');
		var self = this;

		row.id && $notification.data('id', row.id).on('click', function(e) {
			self.read($(this).data('id'));
		});

		row.type && $('<div class="notification-title text-' + row.color + '" />').text(row.type.toUpperCase()).appendTo($notification);
		row.message && $('<div class="notification-description" />').html(row.message).appendTo($notification);
		row.date && $('<div class="notification-ago margin-xs-vr" />').html(row.date.fromNow()).appendTo($notification);
		row.icon && $('<div class="notification-icon fa fa-' + row.icon + ' bg-' + row.color + '" />').appendTo($notification);

		return $notification;
	},
	notificationIsShowed: function(id) {
		return (this.showed.length && _.find(this.showed, function (_id) {
			return _id == id;
		}) !== undefined);
	}
}