CMS.Notifications = {
	list: {},
	showed: [],
	container: '#notifications-container',
	emptyMessageShowed: false,
	scrollInited: false,
	init: function () {
		this.fetchList();
	},
	create: function (data) {
		var isset = this.list.length && _.find(this.list, function (row) {
			return row.id == data.id;
		});

		!isset && this.list.push(this.prepareData(data));
	},
	show: function (data) {
		var $cont = this.getContainer();
		if(this.notificationIsShowed(data.id)) return;

		this.fetchNotification(this.prepareData(data)).prependTo($cont);
		this.showed.push(data.id);

		this.updateCounter();
	},
	prepareData: function(data) {
		data.date = moment(data.date);
		data.color = data.color || 'default';
		data.type = data.type || 'Info';

		return data;
	},
	read: function(id) {
		var self = this;
		Api.delete('/api.notification.read', {id: id}, function() {
			self.deleteNotifiction(id);
		});
	},
	updateCounter: function () {
		if(this.getTotal() > 0) {
			this.emptyMessageShowed && this.removeEmptyMessage();
			!this.scrollInited && this.getContainer().slimScroll({
				height: 250
			});
		} else {
			this.emptyMessageShowed || this.showEmptyMessage();
			if(this.scrollInited) {
				this.getContainer().slimScroll({
					destroy:true
				});
			}
		}
		$('.counter', this.container).text(this.getTotal());
	},
	showEmptyMessage: function() {
		this.emptyMessageShowed = true;
		this.getContainer().append($('<div class="popover-content bg-info">' + i18n.t('notifications.core.no_unread_messages') + '</div>'));
	},
	removeEmptyMessage: function() {
		this.emptyMessageShowed = false;
		this.getContainer().find('.popover-content').remove();
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

		this.updateCounter();
	},
	deleteNotifiction: function(id) {
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
			$(this).remove();
			e.preventDefault();
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