CMS.notifications = {
	_init: false,
		_list: [],
		counter: 0,
		container: '#notifications-container',
		add: function(text, created_on, title, type, icon, counter) {
		this._list.push([text, moment(created_on), title, type, icon, counter]);
	},
	update_counter: function() {
		this.counter++;
	},
	init: function() {
		this._build();

		$('.notifications-list', this.container).slimScroll({ height: 250 });
		this._init = true;
	},
	_build_row:function(row) {
		var text = row[0],
			created_on = row[1].fromNow(),
			title = row[2],
			type = row[3],
			icon = row[4];

		var $notification = $('<div class="notification" />');
		if(!type) var type = '';

		if(title)
			$('<div class="notification-title '+type+'" />').text(__(title).toUpperCase()).prependTo($notification);

		if(text)
			$('<div class="notification-description" />').html(text).appendTo($notification);

		if(created_on)
			$('<div class="notification-ago margin-xs-vr" />').html(created_on).appendTo($notification);

		if(icon)
			$('<div class="notification-icon fa fa-'+icon+'" />').appendTo($notification);

		return $notification;
	},
	_build: function() {
		var $cont = $('.notifications-list', this.container);

		this._list = _.sortBy(this._list, function(row) {
			return !row[1].unix();
		});

		for(i in this._list) {
			$notification = this._build_row(this._list[i]);
			$notification.prependTo($cont);

			if(this._list[i][5] !== false)
				this.update_counter();

			delete(this._list[i]);
		}

		$('.counter', this.container).text(parseInt(this.counter));
	}
}