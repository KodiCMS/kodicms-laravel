CMS.messages = {
	init: function() {
		if (typeof MESSAGE_ERRORS == 'undefined') return;
		this.parse(MESSAGE_ERRORS, 'error');
		this.parse(MESSAGE_SUCCESS);

		$('body').on('show_message', $.proxy(function() {
			var messages = _.toArray(arguments).slice(1);
			this.parse(messages);
		}, this));
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
	show: function(msg, type, icon) {
		if(!type) type = 'success';

		window.top.noty({
			layout: 'topRight',
			type: type,
			icon: icon || 'fa fa-ok',
			text: decodeURIComponent(msg)
		});
	},
	error: function (message) {
		this.show(message, 'error');
	}
}

CMS.error_field = function(name, message) {
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
}

CMS.clear_error = function($container, $clear_tabs_error) {
	var $group = $('.form-group');
	if(typeof $container == 'object')
		$group = $('.form-group', $container);

	$group
		.removeClass('has-error')
		.find('.error-message')
		.remove();

	if($clear_tabs_error !== false)
		$('.nav-tabs li a').removeClass('tab-error');
}