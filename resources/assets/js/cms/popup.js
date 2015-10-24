var Popup = {
	_target: null,
	_defaults: {
		fixed: true,
		width: '95%',
		height: '93%',
		maxWidth:'95%',
		maxHeight:'93%',
		transition: 'fade',
		opacity: 0.4,
		speed: 100,
		close: '<i class="fa fa-times fa-fw" />',
		onLoad: function(a, b) {
			Popup._target = a;
		}
	},
	_resizeTimer: null,
	defaults: function() {
		return this._defaults;
	},
	openHTML: function(html, params, parent) {
		if(!params) var params = {};

		if(html instanceof jQuery)
			var options = {
				inline: true,
				href: html
			};
		else
			var options = {
				html: html
			};

		return this.get($.extend(params, options), parent);
	},
	openIframe: function(href, params, parent) {
		if(!params) var params = {};

		return this.openUrl(href, $.extend(params, {
			iframe: true
		}), parent);
	},
	openUrl: function(href, params, parent) {
		if(!params) var params = {};

		var url = href.split("?")[0];
		var query_string = $.query.load(href).set('type', 'iframe').toString();

		href = url + query_string;
		return this.get($.extend(params, {
			href: href
		}), parent);
	},
	close: function() {
		window.top.$.colorbox.close();
	},
	get: function(options, parent) {
		$(window).resize(this.resize);

		var options = $.extend({}, this.defaults(), options);

		if(parent)
			return window.top.$.colorbox(options);

		return $.colorbox(options);
	},
	resize: function resizeColorBox() {
		if (this._resizeTimer) clearTimeout(this._resizeTimer);
		this._resizeTimer = setTimeout(function() {
			$.colorbox.resize({
				width: '95%',
				height: '93%',
				speed: 100
			});
		}, 500);
	}
};

CMS.ui.add('popup', function ()
{
	$('body').on('click', '.popup', function(e) {
		e.preventDefault();

		var type = $(this).data('popup-type');
		var params = $(this).data('popup-params');
		var parent = $(this).data('popup-parent');

		switch (type)
		{
			case 'html':
				return Popup.openHTML($(this), params, parent);
			case 'url':
			case 'href':
			case 'ajax':
				return Popup.openUrl($(this).prop('href'), params, parent);
			default:
				return Popup.openIframe($(this).prop('href'), params, parent);

		}
	});

	var $form = $('form');
	var $form_actions = $('.iframe .form-actions').add('.form-popup');
	var method = $form.data('api-method');
	var action = $form.data('api-url');

	if((method && method.length > 0 && action && action.length > 0)) {
		$('.btn-save', $form_actions).on('click', function (e) {
			Api[method](action, $form);
			e.preventDefault();
		});

		$('.btn-save-close', $form_actions).on('click', function (e) {
			Api[method](action, $form, function (response) {
				(response.code == 200) &&Popup.close();
			});

			e.preventDefault();
		});
	}

	$('.btn-close', $form_actions).on('click', function (e) {
		Popup.close();
		e.preventDefault();
	});
});