var cms = {
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
				
				cms.messages.parse(messages);
			});
		},
		parse: function($messages, $type) {
			for(text in $messages) {
				if(text == '_external') {
					this.parse($messages[text], $type);
					continue;
				}

				if($type == 'error'){
					cms.error_field(text, decodeURIComponent($messages[text]));
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
				cms.messages.error('System try to add filter without required callbacks.', name, switchOn_handler, switchOff_handler);
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
								if(window.top.cms.filters.exec(object, 'insert', file))
									window.top.$.fancybox.close();
							}
						}
					});
				}
			});
		}
	}
};

cms.addTranslation = function (obj) {
    for (var i in obj) {
        cms.translations[i] = obj[i];
    }
};

cms.ui = {
    callbacks:[],
    add:function (module, callback) {
        if (typeof(callback) != 'function')
            return this;

        cms.ui.callbacks.push([module, callback]);
		
		return this;
    },
    init:function (module) {
		$('body').trigger('before_ui_init');
        for (var i = 0; i < cms.ui.callbacks.length; i++) {
			try {
				if(!module)
					cms.ui.callbacks[i][1]();
				else if(_.isArray(module) && _.indexOf(module, cms.ui.callbacks[i][0]) != -1 ) {
					cms.ui.callbacks[i][1]();
				}
				else if (module == cms.ui.callbacks[i][0]) {
					cms.ui.callbacks[i][1]();
				}
			} catch (e) {
				console.log(cms.ui.callbacks[i][0], e);
			}
        }
		$('body').trigger('after_ui_init');
    }
};

cms.init = {
	callbacks:[],
	add:function (rout, callback) {
		if (typeof(callback) != 'function')
			return this;

		if (typeof(rout) == 'object') {
			for (var i = 0; i < rout.length; i++)
				cms.init.callbacks.push([rout[i], callback]);
		} else if (typeof(rout) == 'string') {
			cms.init.callbacks.push([rout, callback]);
		}
		
		cms.init.callbacks.reverse();
		return this;
	},
	run:function () {
		$('body').trigger('before_cms_init');
		
		var body_id = $('body:first').attr('id');

		for (var i = 0; i < cms.init.callbacks.length; i++) {
			var rout_to_id = 'body.' + cms.init.callbacks[i][0];

			if (body_id == rout_to_id)
				cms.init.callbacks[i][1]();
		}
		
		$('body').trigger('after_cms_init');
	}
};

var Api = {
	_response: null,
	get: function(uri, data, callback, async) {
		var request = this.request('GET', uri, data, callback, async);
		
		if(async === false)
			this._response = request.responseJSON;

		return this.response();
	},
	post: function(uri, data, callback, async) {
		var request = this.request('POST', uri, data, callback, async);
		
		if(async === false)
			this._response = request.responseJSON;

		return this.response();
	},
	put: function(uri, data, callback, async) {
		var request = this.request('PUT', uri, data, callback, async);
		
		if(async === false)
			this._response = request.responseJSON;

		return this.response();
	},
	'delete': function(uri, data, callback, async) {
		var request = this.request('DELETE', uri, data, callback, async);
	
		if(async === false)
			this._response = request.responseJSON;

		return this.response();
	},
	request: function(method, uri, data, callback, async) {
		url = Api.build_url(uri);
		
		var obj = new Object();

		$.ajaxSetup({
			contentType : 'application/json'
		});

		if(typeof(data) == 'object' && method != 'GET') 
			data = JSON.stringify(data);

		return $.ajax({
			type: method,
			url: url,
			data: data,
			dataType: 'json',
			async: async !== false,
			success: function(response) {
				if(response.code != 200) {
					if(typeof(callback) == 'function') callback(response);
					return Api.exception(response);
				}

				if (response.message) {
					cms.clear_error();

					if(response.message instanceof Object) {
						cms.messages.parse(response.message);
					} else {
						cms.messages.show(response.message);
					}
				}
	
				if(response.redirect) {
					$.get(window.top.CURRENT_URL, function(resp){
						window.location = response.redirect + '?type=iframe';
					});
				}
				this._response = response;
				
				var $event = method + url.replace(SITE_URL, ":").replace(/\//g, ':');
				window.top.$('body').trigger($event.toLowerCase(), [this._response.response]);

				if(typeof(callback) == 'function') callback(this._response);
			},
			error: function(jqXHR, textStatus, errorThrown) {
				if(typeof(callback) == 'function') callback(textStatus);
			}
		});
	},
	build_url: function(uri) {
		if(uri.indexOf(BACKEND_PATH) !== -1)
			uri = uri.substring(uri.indexOf(BACKEND_PATH) + BACKEND_PATH.length);

		if(uri.indexOf('-') == -1)
		{
			uri = '-' + uri;
		}
		else if(uri.indexOf('-') > 0 && (uri.indexOf('/') == -1 || uri.indexOf('/') > 0))
		{
			uri = '/' + uri;
		}
		
		if(uri.indexOf('/api') == -1)
		{
			uri = 'api' + uri;
		}
		
		if(uri.indexOf(BACKEND_PATH) == -1)
		{
			// Add the ADMIN DIR NAME
			if(uri.indexOf('/') != 0)
			{
				uri = BACKEND_PATH + '/' + uri; 
			}
			else
			{
				uri = BACKEND_PATH + uri; 
			}	
		}
		
		if(uri.indexOf(SITE_URL) == -1)
		{
			// Add SITE_URL.
			uri = SITE_URL + uri;
		}
		
		return uri;
	},
	exception: function(response) {
		if(response.code == 120 && typeof(response.errors) == 'object') {
			cms.clear_error();
			for(i in response.errors) {
				cms.messages.error(response.errors[i], 'error');
				cms.error_field(i, response.errors[i]);
			}
		} else if (response.message) {
			cms.clear_error();
			cms.messages.error(response.message, 'error');
		}
	},
	response: function() {
		return this._response;
	}
};

// Run
$(function() {
	cms.ui.init();
	KodiCMS.start(null, cms.settings);
	
	cms.init.run();
	
	setTimeout(function() {
		cms.notifications.init();
	}, 1500);

	cms.messages.init();
});

