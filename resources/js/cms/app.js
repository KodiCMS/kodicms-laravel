var CMS = {
	models: {},
	views: {},
	collections: {},
	routes: {},
	settings: {},
	popup_target: null,

	messages: {
		init: function() {
			this.parse(MESSAGE_ERRORS, 'error');
			this.parse(MESSAGE_SUCCESS);

			$('body').on('show_message', function() {
				var messages = _.toArray(arguments).slice(1);

				CMS.messages.parse(messages);
			});
		},
		parse: function($messages, $type) {
			for(text in $messages) {
				if(text == '_external') {
					this.parse($messages[text], $type);
					continue;
				}

				if($type == 'error'){
					CMS.error_field(text, decodeURIComponent($messages[text]));
				}

				this.show($messages[text], $type);
			}
		},
		show: function(msg, type) {
			if(!type) type = 'success';

			var title = type.charAt(0).toUpperCase() + type.slice(1);
			window.top.$.pnotify({
				title: __(title),
				text: decodeURIComponent(msg),
				sticker: false,
				nonblock: true,
				delay: 4000,
				type: type,
				history: false
			});
		},
		error: function (message) {
			this.show(message, 'error');
		}
	},

	notifications: {
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
	},

	error_field: function(name, message) {
		var gpoups = $('.form-group:not(.has-error)');

		if(typeof name == 'object')
			var input = name;
		else {
			name = name.indexOf('.') !== -1 ? '['+name.replace(/\./g, '][') + ']' : name;
			var input = $(':input[name*="' + name + '"]', gpoups);
		}

		input
			.after('<span class="help-block error-message">' + message + '</span>')
			.closest('.form-group')
			.addClass('has-error');

		var $tab_id = input.closest('.tab-pane').prop('id');
		if($tab_id) {
			var $tab = $('.nav-tabs a[href="#'+$tab_id+'"]').addClass('tab-error');

			$tab.closest('.tabdrop').find('.dropdown-toggle').addClass('tab-error');
		}


	},
	clear_error: function($container, $clear_tabs_error) {
		var $group = $('.form-group');
		if(typeof $container == 'object')
			$group = $('.form-group', $container);

		$group
			.removeClass('has-error')
			.find('.error-message')
			.remove();

		if($clear_tabs_error !== false)
			$('.nav-tabs li a').removeClass('tab-error');
	},
	loader: {
		counter: 0,
		init: function (container) {
			container = $('body');

			return $('<div class="_loader_container"><span>' + __('Loading') + '</span></div>')
				.appendTo(container)
				.css({
					width: container.outerWidth(true),
					height: container.outerHeight(true),
					top: container.offset().top,
					left: container.offset().left
				})
				.prop('id', 'loader' + ++this.counter);
		},
		show: function (container, speed) {
			if(!speed) {
				speed = 500;
			}

			var loader = this.init(container).fadeTo(speed, 0.4);
			return this.counter;
		},
		hide: function (id) {
			if(!id)
				cont = $('._loader_container');
			else
				cont = $('#loader'+id);

			cont.stop().fadeOut(400, function() {
				$(this).remove();
			});
		}
	},

	/**
	 * Вычисление высоты контейнера с контентом
	 */
	content_height: null,
	calculateContentHeight: function() {
		if(this.content_height != null)
			return this.content_height;

		var contentCont = $('#content-wrapper'),
			headerCont = $('#main-navbar'),
			footerCont = $('footer'),
			windowCont = $(window);

		var contentContHeight = windowCont.outerHeight() - headerCont.outerHeight(),
			contentContPadding = contentCont.outerHeight(!$('body').hasClass('iframe')) - contentCont.innerHeight() + ($('body').hasClass('iframe')) ? 0 : 140;

		this.content_height = contentContHeight - contentContPadding;

		return this.content_height;
	},

	translations: {},

	// Plugins
	plugins: {},

	filters: {
		filters: [],
		switchedOn: {},
		editors: {},
		add: function (name, switchOn_handler, switchOff_handler, exec_handler) {
			if (switchOn_handler == undefined || switchOff_handler == undefined) {
				CMS.messages.error('System try to add filter without required callbacks.', name, switchOn_handler, switchOff_handler);
				return;
			}
			this.filters.push([ name, switchOn_handler, switchOff_handler, exec_handler ]);
		},
		switchOn: function (textarea_id, filter, params) {
			$('#' + textarea_id).css('display', 'block');
			if (this.filters.length > 0) {
				var old_filter = this.get(textarea_id);
				var new_filter = null;

				for (var i = 0; i < this.filters.length; i++) {
					if (this.filters[i][0] == filter) {
						new_filter = this.filters[i];
						break;
					}
				}
				if(old_filter !== new_filter) {
					this.switchOff(textarea_id);
				}
				try {
					this.switchedOn[textarea_id] = new_filter;
					this.editors[textarea_id] = new_filter[1](textarea_id, params);
					$('#' + textarea_id).trigger('filter:switch:on', this.editors[textarea_id]);
				}
				catch (e) {}
			}
		},
		switchOff: function (textarea_id) {
			var filter = this.get(textarea_id);
			try {
				if ( filter && typeof(filter[2]) == 'function' ) {
					filter[2](this.editors[textarea_id], textarea_id);
				}
				this.switchedOn[textarea_id] = null;
				$('#' + textarea_id).trigger('filter:switch:off');
			}
			catch (e) {}
		},
		get: function(textarea_id) {
			for (var key in this.switchedOn) {
				if ( key == textarea_id )
					return this.switchedOn[key];
			}
			return null;
		},
		exec: function(textarea_id, command, data) {
			var filter = this.get(textarea_id);
			if( filter && typeof(filter[3]) == 'function' )
				return filter[3](this.editors[textarea_id], command, textarea_id, data);
			return false;
		}
	},
	filemanager: {
		open: function(object, type) {
			return $.fancybox.open({
				href : BASE_URL + '/elfinder/',
				type: 'iframe'
			}, {
				autoSize: false,
				width: 1000,
				afterLoad: function() {
					this.content[0].contentWindow.elfinderInit({
						getFileCallback: function(file) {
							if(_.isObject(file)) {
								file = file.url;
							}
							if(_.isObject(object)) {
								object.val(file);
								window.top.$.fancybox.close();
							}
							else {
								if(window.top.CMS.filters.exec(object, 'insert', file))
									window.top.$.fancybox.close();
							}
						}
					});
				}
			});
		}
	}
};

CMS.ui = {
	_elements:[],
	add:function (module, callback) {
		if (typeof(callback) != 'function')
			return this;

		CMS.ui._elements.push([module, callback]);
		return this;
	},
	init:function (module) {
		$('body').trigger('ui.init.before');
		for (var i = 0; i < CMS.ui._elements.length; i++) {
			try {
				if(!module)
					CMS.ui._elements[i][1]();
				else if(_.isArray(module) && _.indexOf(module, CMS.ui._elements[i][0]) != -1 )
					CMS.ui._elements[i][1]();
				else if (module == CMS.ui._elements[i][0])
					CMS.ui._elements[i][1]();
			} catch (e) {}
		}
		$('body').trigger('ui.init.after');
	}
};

CMS.controllers = {
	_controllers: [],
	add: function (rout, callback) {
		if (typeof(callback) != 'function')
			return this;

		if (typeof(rout) == 'object')
			for (var i = 0; i < rout.length; i++)
				CMS.controllers._controllers.push([rout[i], callback]);
		else if (typeof(rout) == 'string')
			CMS.controllers._controllers.push([rout, callback]);

		CMS.controllers._controllers.reverse();
		return this;
	},
	call: function () {
		$('body').trigger('controller.call.before');

		var body_id = $('body:first').attr('id');
		for (var i = 0; i < CMS.controllers._controllers.length; i++)
			if (body_id == 'body.' + CMS.controllers._controllers[i][0])
				CMS.controllers._controllers[i][1](CMS.controllers._controllers[i][0]);

		$('body').trigger('controller.call.after');
	}
}