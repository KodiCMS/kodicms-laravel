var Api = {
	_response: null,
	get: function(uri, data, callback, async) {
		return this.request('GET', uri, data, callback, async);
	},
	post: function(uri, data, callback, async) {
		return this.request('POST', uri, data, callback, async);
	},
	put: function(uri, data, callback, async) {
		return this.request('PUT', uri, data, callback, async);
	},
	'delete': function(uri, data, callback, async) {
		return this.request('DELETE', uri, data, callback, async);
	},
	request: function(method, uri, data, callback, async) {
		var url = this.parseUrl(uri);

		$.ajaxSetup({
			contentType : 'application/json'
		});

		if(data instanceof jQuery) {
			data = Api.serializeObject(data);
		}

		if(typeof(data) == 'object' && method.toLowerCase() != 'get') {
			data = JSON.stringify(data);
		}

		return $.ajax({
			type: method,
			url: url,
			data: data,
			dataType: 'json',
			async: async !== false
		})
			.done(function(response) {
				this._response = response;

				if(response.code != 200)
					return Api.exception(response, callback);

				if(response.message)
					CMS.messages.show(response.message, 'success', 'fa fa-exclamation-triangle');

				window.top.$('body').trigger(Api.getEventKey(method, url), [this._response]);

				if(typeof(callback) == 'function') callback(this._response);
			})
			.fail(function(e) {
				return Api.exception(e.responseJSON, callback);
			});
	},
	parseUrl: function(url) {
		return url;
	},
	getEventKey: function(method, url) {
		var event = method + url.replace(SITE_URL, ":").replace(/\//g, ':');
		return event.toLowerCase()
	},
	serializeObject: function(form) {
		var json = {},
			push_counters = {},
			patterns = {
				"validate": /^[a-zA-Z][a-zA-Z0-9_]*(?:\[(?:\d*|[a-zA-Z0-9_]+)\])*$/,
				"key":      /[a-zA-Z0-9_]+|(?=\[\])/g,
				"push":     /^$/,
				"fixed":    /^\d+$/,
				"named":    /^[a-zA-Z0-9_]+$/
			};

		var build = function(base, key, value){
			base[key] = value;
			return base;
		};

		var push_counter = function(key){
			if(push_counters[key] === undefined){
				push_counters[key] = 0;
			}
			return push_counters[key]++;
		};

		$.each(form.serializeArray(), function(){
			// skip invalid keys
			if(!patterns.validate.test(this.name)){
				return;
			}
			var k,
				keys = this.name.match(patterns.key),
				merge = this.value,
				reverse_key = this.name;

			while((k = keys.pop()) !== undefined){
				// adjust reverse_key
				reverse_key = reverse_key.replace(new RegExp("\\[" + k + "\\]$"), '');
				// push
				if(k.match(patterns.push)){
					merge = build([], push_counter(reverse_key), merge);
				}
				// fixed
				else if(k.match(patterns.fixed)){
					merge = build([], k, merge);
				}
				// named
				else if(k.match(patterns.named)){
					merge = build({}, k, merge);
				}
			}
			json = $.extend(true, json, merge);
		});
		return json;
	},
	exception: function(response, callback) {
		if(typeof(callback) == 'function')
			callback(response);

		switch (response.code) {
			case 220: // ERROR_PERMISSIONS

				break;
			case 110: // ERROR_MISSING_PAPAM
				CMS.messages.show(response.message, 'error', 'fa fa-exclamation-triangle');
				for(i in response.fields)
					CMS.error_field(response.fields[i], 'Required');
				break;
			case 120: // ERROR_VALIDATION
				for(i in response.errors){
					CMS.messages.show(response.errors[i], 'error', 'fa fa-exclamation-triangle');
					CMS.error_field(i, response.errors[i].join(', '));
				}
				break;
			case 130: // ERROR_UNKNOWN
			case 140: // ERROR_TOKEN
			case 150: // ERROR_MISSING_ASSIGMENT

				break;
			case 301: // Redirect
			case 302: // Redirect
				if(REQUEST_TYPE == 'iframe') {
					var pos = response.targetUrl.indexOf('?');
					if(pos != -1) {
						response.targetUrl += '&type=iframe';
					} else response.targetUrl += '?type=iframe';
				}

				window.location.href = response.targetUrl;
				break;
			case 403: // ERROR_UNAUTHORIZED
			case 404: // ERROR_PAGE_NOT_FOUND

				break;
		}
	},
	response: function() {
		return this._response;
	}
};

CMS.ui.add('api_buttons', function () {
	$('.btn[data-api-url]').on('click', function (e) {
		e.preventDefault();
		var $self = $(this);

		var $callback = function (response) {};
		var $url = $self.data('api-url');
		if (!$url) return;

		var $callback = $self.data('callback');
		if ($callback) $callback = window[$callback];
		else $callback = function (response) {};

		var $method = $self.data('method'),
			$reload = $self.data('reload'),
			$params = $self.data('params'),
			$preloader = $self.data('preloader');

		if ($reload) {
			if ($reload === true) $callback = function () { window.location = '' }
			else $callback = function () { window.location = $reload }
		}

		if (!$method) $method = 'GET';

		if($preloader) {
			var container = typeof($preloader) == "string" ? $preloader : 'body';
			var loader = CMS.loader.show(container);
			$(window).on(Api.getEventKey($method, Api.parseUrl($url)), function(e) {
				CMS.loader.hide(loader);
			});
		}

		Api.request($method, $url, $params, $callback);
	})
}).add('ajax_form', function () {
	$('body').on('submit', 'form.form-ajax', function () {
		var $self = $(this),
			$buttons = $('button', $self).attr('disabled', 'disabled'),
			$action = $self.attr('action');

		if ($self.data('ajax-action'))
			$action = $self.data('ajax-action');

		var loader = CMS.loader.show($(this));

		Api.post($action, $self, function (response) {
			setTimeout(function () {
				$buttons.removeAttr('disabled');
				CMS.loader.hide(loader);
			}, 1000);
		});

		return false;
	});
});