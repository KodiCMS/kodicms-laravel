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
			var rout_to_id = 'body_' + cms.init.callbacks[i][0];

			if (body_id == rout_to_id)
				cms.init.callbacks[i][1]();
		}
		
		$('body').trigger('after_cms_init');
	}
};

cms.ui.add('flags', function() {
	$('body').on('click', '.flags .label', function(e) {
		var $src = $(this).parent().data('target');
		if( ! $src ) $src = $(this).parent().prevAll(':input');
		else $src = $($src);
		
		var $container = $(this).parent();
		var $append = $container.data('append') == true;
		var $array = $container.data('array') == true;
		var $value = $(this).data('value');
		
		if($array) $value = $value.split(',');

		if($append) {
			if($src.is(':input')) {
				var $values = $src.val().split(', ');
				$values.push($value);
				$values = _.uniq(_.compact($values));
				$src.val($values.join(', '));
			}
			else {
				var $values = $src.text().split(', ');
				$values.push($value);
				$values = _.uniq(_.compact($values));
				$src.text($values.join(', '));
			}
			
			$('.label', $container).removeClass('label-success');
			for(i in $values) {
				$('.label[data-value="'+$values[i]+'"]').addClass('label-success');
			}
			
		} else {
			$('.label', $container).removeClass('label-success');
			$(this).addClass('label-success');

			if($src.hasClass('select2-offscreen'))
			{
				$src.select2("val", $value);
			}
			else if($src.is(':input'))
			{
				$src.val($value);
			}
			else {
				$src.text($value);
			}
		}
		
		e.preventDefault();
	});
}).add('btn-confirm', function() {
	$('body').on('click', '.btn-confirm', function (e) {
		if (!confirm(__('Are you sure?')))
			e.preventDefault();
	});
}).add('calculate_height', function() {
	cms.calculateContentHeight();

	$(window).resize(function() {
		cms.content_height = null;
		cms.calculateContentHeight();
	});
}).add('panel-toggler', function() {
	var icon_open = 'fa-chevron-up',
		icon_close = 'fa-chevron-down';

	$('.panel-toggler')
		.click(function () {
			var $self = $(this);
	
			if($self.data('target-')) {
				$_cont = $($self.data('target-'));
			} else {
				var $_cont = $self.next('.panel-spoiler');
			}
		
			$_cont.slideToggle('fast', function() {
				var $icon = $self.find('.panel-toggler-icon');
				if($(this).is(':hidden')) {
					$icon.removeClass(icon_open).addClass(icon_close).addClass('fa');
				} else {
					$icon.addClass(icon_open).removeClass(icon_close).addClass('fa');
				}
			});
			
			return false;
		}).each(function() {
			if($(this).data('hash') == window.location.hash.substring(1))
			{
				$(this).click();
				$('html,body').animate({scrollTop: $(this).offset().top}, 'slow');
			}
		})
		.append('<div class="panel-heading-controls"><span class="text-sm"><i class="panel-toggler-icon fa '+icon_close+'" />&nbsp;&nbsp;&nbsp;'+__('Toggle')+'</span></div>');

}).add('datepicker', function() {

	var options = {
		format: 'Y-m-d H:i:00',
		lang: LOCALE,
		dayOfWeekStart: 1
	};
	
	$('.datetimepicker').each(function() {
		var local_options = $.extend({}, options);
		var $self = $(this);
		
		if($self.data('range-max-input')) {
			local_options['onShow'] = function(ct) {
				var $input = $($self.data('range-max-input'));
				this.setOptions({
					maxDate: $input.val() ? $input.val() : false
				});
			}
		}
		
		if($self.data('range-min-input')) {
			local_options['onShow'] = function(ct) {
				var $input = $($self.data('range-min-input'));
				this.setOptions({
					minDate: $input.val() ? $input.val() : false
				});
			}
		}
		
		$self.datetimepicker(local_options);
	});
	
	$('.datepicker').each(function() {
		var local_options = $.extend(options, {
			timepicker: false,
			format: 'Y-m-d'
		});
		
		var $self = $(this);
		
		if($self.data('range-max-input')) {
			local_options['onShow'] = function( ct ){
				var $input = $($self.data('range-max-input'));
				this.setOptions({
					maxDate: $input.val() ? $input.val() : false
				});
			}
		} else if($self.data('range-min-input')) {
			local_options['onShow'] = function( ct ){
				var $input = $($self.data('range-min-input'));
				this.setOptions({
					minDate: $input.val() ? $input.val() : false
				});
			}
		}

		$self.datetimepicker(local_options);
	});
	
	$('.timepicker').each(function() {
		var local_options = $.extend(options, {
			timepicker: true,
			datepicker: false,
			format: 'H:i:s'
		});
		
		var $self = $(this);
		
		$self.datetimepicker(local_options);
	});
}).add('slug', function() {

    var slugs = {};
    $('body').on('keyup', '.slug-generator', function () {
		var $slug_cont = $('.slug');

		if($(this).data('slug')) {
			$slug_cont = $($(this).data('slug'));
		}
		
		$separator = '-';
		if($(this).data('separator')) {
			$separator = $(this).data('separator');
		}
		
        if ($slug_cont.is(':input') && $slug_cont.val() == '')
            slugs[$slug_cont] = true;
		
		$slug_cont.on('keyup', function() {
			slugs[$slug_cont] = false;
		});

        if (slugs[$slug_cont]) {
			var slug = getSlug($(this).val(), {
				separator: $separator
			});
			
			if($slug_cont.is(':input'))
				$slug_cont.val(slug);
			else
				$slug_cont.text(slug);
        }
    });

	$('body').on('keyup', '.slug', function () {
		var c = String.fromCharCode(event.keyCode);
		var isWordcharacter = c.match(/\w/);
		
		if( ! isWordcharacter && event.keyCode != '32') return;
		
		$separator = '-';
		if($(this).data('separator')) {
			$separator = $(this).data('separator');
		}
		
		var slug = getSlug($(this).val(), {
			separator: $separator
		});
		
		$(this).val(slug);
		slugs[$(this)] = false;

		if ($(this).val() == '')
			slugs[$(this)] = true;
	});

}).add('dropzone', function() {
	// Disable auto discover for all elements:
	Dropzone.autoDiscover = false;

	if (!$('.dropzone').length) {
		return;
	};
	
	cms.uploader = new Dropzone('.dropzone', {
		success: function(file, r) {
			var response = $.parseJSON(r);
			var self = this;
			if(response.code != 200) {
				cms.messages.error(response.message);
				
			} else if(response.message) {
				cms.messages.show(response.message);
			}
			
			$(file.previewElement).fadeOut(500, function() {
				self.removeFile(file);
			});
		},
		error: function(file, message) {
			cms.messages.error(message);
			this.removeFile(file);
		},
        dictDefaultMessage: __("Drop files here to upload"),
        dictFallbackMessage: __("Your browser does not support drag'n'drop file uploads."),
        dictFallbackText: __("Please use the fallback form below to upload your files like in the olden days."),
        dictFileTooBig: __("File is too big ({{filesize}}MB). Max filesize: {{maxFilesize}}MB."),
        dictInvalidFileType: __("You can't upload files of this type."),
        dictResponseError: __("Server responded with {{statusCode}} code."),
        dictCancelUpload: __("Cancel upload"),
        dictCancelUploadConfirmation: __("Are you sure you want to cancel this upload?"),
        dictRemoveFile: __("Remove file"),
        dictMaxFilesExceeded: __("You can only upload {{maxFiles}} files."),
	});
}).add('fancybox', function() {
    $(".fancybox-image").fancybox();
}).add('popup', function() {
	$(".popup").fancybox({
		fitToView	: true,
		autoSize	: false,
		width		: '99%',
		height		: '99%',
		openEffect	: 'none',
		closeEffect	: 'none',
		beforeLoad: function() {
			var url = this.href.split("?")[0];
			var query_string = $.query.load(this.href).set('type', 'iframe').toString();
			
			this.href = url + query_string;
			var title = this.element.data('title');
			if(title !== false) {
				this.title = title ? title : this.element.html();
			}
			
			cms.popup_target = this.element;
		},
		helpers : {
    		title : {
    			type : 'inside',
				position: 'top'
    		}
    	}
	});

	var method = ACTION == 'add' ? 'put' : 'post';
	var $form_actions = $('.iframe .form-actions');
	
	var $action = CONTROLLER;

	if((typeof API_FORM_ACTION != 'undefined'))
		$action = API_FORM_ACTION;

	$('.btn-save', $form_actions).on('click', function(e) {
		var $data = $('form').serializeObject();
		Api[method]($action, $data);
		
		e.preventDefault();
	});

	$('.btn-save-close', $form_actions).on('click', function(e) {
		var $data = $('form').serializeObject();
		Api[method]($action, $data, function(response) {
			window.top.$.fancybox.close();
		});
		e.preventDefault();
	});

	$('.btn-close', $form_actions).on('click', function(e) {
		window.top.$.fancybox.close();
		e.preventDefault();
	});
	
	if(CLOSE_POPUP)
		setTimeout(function() {
			window.top.$.fancybox.close();
		}, 1000);
}).add('select2', function() {
	if(!TAG_SEPARATOR) var TAG_SEPARATOR = ',';

	$('select').not('.no-script').select2();
	$('.tags').select2({
		tags: [],
		minimumInputLength: 0,
		tokenSeparators: [TAG_SEPARATOR],
		createSearchChoice: function(term, data) {
			if ($(data).filter(function() {
				return this.text.localeCompare(term) === 0;
			}).length === 0) {
				return {
					id: term,
					text: term
				};
			}
		},
		multiple: true,
		ajax: {
			url: Api.build_url('tags'),
			dataType: "json",
			data: function(term, page) {
				return {term: term};
			},
			results: function(data, page) {
				if(!data.response) return {results: []};
				return {results: data.response};
			}
		},
		initSelection: function(element, callback) {
			var data = [];
			
			var tags = element.val().split(",");
			for(i in tags) {
				data.push({
					id: tags[i],
					text: tags[i]
				});
			};
			callback(data);
		}
	});
}).add('ajax_form', function() {
	$('body').on('submit', 'form.form-ajax', function() {
		var $self = $(this),
			$buttons = $('button', $self).attr('disabled', 'disabled'),
			$action = $self.attr('action');

		if($self.data('ajax-action'))
			$action = $self.data('ajax-action');

		Api.post($action, $self.serialize(), function(response) {
			setTimeout(function() {
				$buttons.removeAttr('disabled');
			}, 5000);
		});

		return false;
	});
}).add('filemanager', function() {
	var $input = $(':input[data-filemanager]');
	
	$input.each(function() {
		var $self = $(this);
		var $btn = $('<button class="btn" type="button"><i class="fa fa-folder-open"></i></button>');
		if($self.next().hasClass('input-group-btn')) {
			$btn.prependTo($self.next());
		} else {
			$btn.insertAfter($self);
		}

		$btn.on('click', function() {
			cms.filemanager.open($self);
		});
		
		$self.removeAttr('data-filemanager');
	})
		
	$('body').on('click', '.btn-filemanager', function() {
		var el = $(this).data('el');
		var type = $(this).data('type');

		if(!el) return false;
		
		cms.filemanager.open(el, type);
		return false;
	});
}).add('hotkeys', function(){
	$('*[data-hotkeys]').each(function() {
		var $self = $(this),
			$hotkeys = $self.data('hotkeys'),
			$callback = function(e){ e.preventDefault(); };
			
		if($self.is(':submit') || $self.hasClass('popup')) {
			$callback = function( e ) {
				$self.trigger('click');
				e.preventDefault();
			} 
		} else if($self.attr('href')) {

			$callback = function( e ) {
				if($self.hasClass('btn-confirm')) {
					if ( ! confirm(__('Are you sure?')))
						return false;
				}
				window.location = $self.attr('href');
				e.preventDefault();
			} 
		} else if($self.hasClass('panel-toggler')) {
			$callback = function( e ) {
				$self.trigger('click');
				$('body').scrollTo($self);
				e.preventDefault();
			} 
		} else if($self.hasClass('nav-tabs')) {
			$callback = function( e ) {
				var $current_li = $self.find('li.active'),
					$next_li = $current_li.next();
				
				if($next_li.hasClass('nav-section')) {
					$next_li = $next_li.next();
				}
				if($current_li.is(':last-child')) {
					$next_li = $self.parent().find('li:first-child');
				}
				
				$next_li.find('a').trigger('click');
				e.preventDefault();
			} 
		} else if($self.is(':checkbox')) {
			$callback = function( e ) {
				if($self.prop("checked"))
					$self.uncheck().trigger('change');
				else
					$self.check().trigger('change');
				e.preventDefault();
			}
		}
		
		$(document).on('keydown', null, $hotkeys, $callback);
	});
	
	// GLOBAL HOTKEYS
	$(document).on('keydown', null, 'shift+f1', function(e) {
		Api.delete('cache');
		e.preventDefault();
	});
	
	$(document).on('keydown', null, 'shift+f3', function(e) {
		Api.get('search.update_index');
		e.preventDefault();
	});
	
	$(document).on('keydown', null, 'shift+f4', function(e) {
		Api.post('layout.rebuild');
		e.preventDefault();
	});
	
	$(document).on('keydown', null, 'ctrl+shift+l', function(e) {
		window.location = '/backend/logout';
		e.preventDefault();
	});
}).add('api_buttons', function(){
	$('.btn[data-api-url]').on('click', function(e) {
		e.preventDefault();
		var $self = $(this);
		
		var $callback = function(response) {};
		var $url = $self.data('api-url');
		if( ! $url) return;
		
		var $callback = $self.data('callback');
		if($callback) 
			$callback = window[$callback];
		else
			$callback = function(response) {};
		
		var $method = $self.data('method'),
			$reload = $self.data('reload'),
			$params = $self.data('params');
		
		if($reload) {
			if($reload === true)
				$callback = function() { window.location = ''}
			else
				$callback = function() { window.location = $reload}
		}
		
		if (!$method)
			$method = 'GET';

		Api.request($method, $url, $params, $callback);
	})
}).add('select_all_checkbox', function() {
	$(document).on('change', 'input[name="check_all"]', function(e) {
		var $self = $(this),
			$target = $self.data('target');
		
		if( ! $target) return false;

		$($target).prop("checked" , this.checked).trigger('change');
		e.preventDefault();
    });
}).add('icon', function() {
	$('*[data-icon]').add('*[data-icon-prepend]').each(function() {
		$(this).html('<i class="fa fa-' + $(this).data('icon') + '"></i> ' + $(this).html());
		$(this).removeAttr('data-icon-prepend').removeAttr('data-icon');
	});
	
	$('*[data-icon-append]').each(function() {
		$(this).html($(this).html() + '&nbsp&nbsp<i class="fa fa-' + $(this).data('icon-append') + '"></i>');
		$(this).removeAttr('data-icon-append');
	});
}).add('tabbable', function() {
	$('.tabbable').each(function(i) {
		var $self = $(this);
		
		if($('> .panel-heading', $self).size() > 0) {
			var $tabs_content = $('<div class="tab-content no-padding-t" />').prependTo($self);
			var $tabs_ul = $('<ul class="nav nav-tabs tabs-generated" style="position:relative; margin-top: 10px;" />').insertBefore($self);
			$('> .panel-heading', $self).each(function(j) {
				var $li = $('<li></li>').appendTo($tabs_ul);
				var $content = $(this).nextUntil('.panel-heading').not('.panel-footer').removeClass('panel-spoiler');

				$(this).find('.panel-title').removeClass('panel-title');
				$(this).find('.panel-heading-controls').remove();
				var $content_container = $('<div class="tab-pane" id="panel-tab-' + i + '' +  '' + j+ '" />').append($content).appendTo($tabs_content);
				$('<a href="#panel-tab-' + i + '' +  '' + j+ '" data-toggle="tab"></a>').html($(this).html()).appendTo($li);

				$(this).remove();
			});

			$('li a', $tabs_ul).on('click', function() {
				window.location.hash = $(this).attr('href');
			});
		}
	});
	
	if(window.location.hash.length > 0 && $('.tabs-generated li a[href='+window.location.hash+']').length > 0) {
		$('li a[href='+window.location.hash+']').parent().addClass('active');
		$('.tabbable .tab-pane' + window.location.hash).addClass('active');
	} else {
		$('.tabs-generated li:first-child').addClass('active');
		$('.tabbable .tab-pane:first-child').addClass('active');
	}
	
	$('.tabs-generated').tabdrop();
});

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
		if(uri.indexOf(ADMIN_DIR_NAME) !== -1)
			uri = uri.substring(uri.indexOf(ADMIN_DIR_NAME) + ADMIN_DIR_NAME.length);

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
		
		if(uri.indexOf(ADMIN_DIR_NAME) == -1)
		{
			// Add the ADMIN DIR NAME
			if(uri.indexOf('/') != 0)
			{
				uri = ADMIN_DIR_NAME + '/' + uri; 
			}
			else
			{
				uri = ADMIN_DIR_NAME + uri; 
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

(function ($) {
	var methods = {
		settings: {
			updateOnResize: false,
			contentHeight: false,
			type: 'height',
			offset: 0,
			minHeight: 0,
			onCalculate: function($target_object, $height) {
				$target_object.css(this.type, $height);
			},
			onResize: function($target_object, $height) {
				$target_object.css(methods.settings.type, $height);
			}
		},
		children: function($object, $target_object, $height) {
			$object.children().each(function() {
				var $self = $(this);

				if($target_object.is($self) || $self.is(':hidden')) return;
				
				if($self.find($target_object).size() > 0) {
					$height -= ($self.outerHeight(true) - $self.outerHeight());
					$height = methods.children($self, $target_object, $height);
				} else {
					$height -= $self.outerHeight(true);
				}
			});
			
			return $height;
		},
		get_calculated_height: function($object, $target_object) {
			var height = this.get_content_height($object);
			height = this.children($object, $target_object, height);
			
			height -= parseInt(methods.settings.offset);
				
			if(height < methods.settings.minHeight)
				height = methods.settings.minHeight;
			
			return height;
		},
		get_content_height: function($object) {
			var height = 0;

			if(this.settings.contentHeight) {
				if((typeof this.settings.contentHeight == "boolean") || this.settings.contentHeight == 'auto')
					height = cms.calculateContentHeight();
				else
					height = parseInt(this.settings.contentHeight);
			} else
				height = $object.height();
			
			return height - 3;
		},
		get_taget_object: function($object, $container) {
			if (typeof $object == 'string' || $object instanceof String)
				return $($object, $container);
			else if($target_object instanceof jQuery)
				return $object;
			else return false;
		}
	}
	
	$.fn.calcHeightFor = function($object, options) {

		methods.settings = $.extend(methods.settings, options);
		var $self = $(this);
		
		$target_object = methods.get_taget_object($object, this);
		if(!$target_object || $self.find($target_object).size() == 0) return;
		
		var contHeight = methods.get_content_height($self);
		contHeight = methods.children($(this), $target_object, contHeight);
	
		return contHeight;
	};
	
	$.fn.setHeightFor = function($object, options) {
		methods.settings = $.extend(methods.settings, options);

		return this.each(function(){
			var $self = $(this);
	
			$target_object = methods.get_taget_object($object, this);
			if(!$target_object || $self.find($target_object).size() == 0) return;
			
			var $size = $target_object.size();
			$target_object.each(function() {
				var $target_object = $(this);
				var height = methods.get_calculated_height($self, $target_object) / $size;
				methods.settings.onCalculate($target_object, height);

				if(methods.settings.updateOnResize) {
					$(window).on('resize', $self, function() {
						var height = methods.get_calculated_height($self, $target_object) / $size;
						methods.settings.onResize($target_object, height);
					});
				}
			});
		});
	};
})(jQuery);